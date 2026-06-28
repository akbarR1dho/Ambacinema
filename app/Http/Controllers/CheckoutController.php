<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Booking\CheckoutRequest;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $orderRepo;
    protected $showtimeRepo;
    protected $seatRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ShowtimeRepositoryInterface $showtimeRepo,
        SeatRepositoryInterface $seatRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->showtimeRepo = $showtimeRepo;
        $this->seatRepo = $seatRepo;
    }

    public function process(CheckoutRequest $request)
    {
        $validated = $request->validated();

        $showtime = $this->showtimeRepo->findWithRelations($request->showtime_id);
        
        // Double check if seats are already booked
        $bookedSeats = $this->orderRepo->checkSeatsAvailability($showtime->id, $request->seats);

        if ($bookedSeats) {
            return back()->withErrors(['seats' => 'One or more selected seats have already been booked. Please try again.']);
        }

        $pricePerSeat = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;
        $totalPrice = count($request->seats) * $pricePerSeat;

        // Create Order
        $order = $this->orderRepo->create([
            'user_id' => Auth::id(),
            'showtime_id' => $showtime->id,
            'total_price' => $totalPrice,
            'status' => 'confirmed', // Assuming auto confirm for simplicity
        ]);

        // Attach seats with UUIDs
        $pivotData = [];
        foreach ($request->seats as $seatId) {
            $pivotData[$seatId] = ['id' => (string) \Symfony\Component\Uid\Uuid::v7()];
        }
        $this->orderRepo->attachSeats($order, $pivotData);

        // Fetch seat names for QR Code
        $seatNames = $this->seatRepo->getSeatNamesByIds($request->seats);

        // Generate QR Code data
        $qrData = json_encode([
            'Order ID' => $order->id,
            'User' => Auth::user()->name,
            'Movie' => $showtime->movie->title,
            'Studio' => $showtime->studio->name,
            'Time' => \Carbon\Carbon::parse($showtime->start_time)->format('Y-m-d H:i'),
            'Seats' => $seatNames
        ]);

        // Generate and save QR Code
        $fileName = 'qrcodes/order_' . $order->id . '_' . Str::random(10) . '.svg';
        
        // Create directory if not exists
        if (!Storage::exists('qrcodes')) {
            Storage::makeDirectory('qrcodes');
        }

        $qrImage = QrCode::format('svg')->size(300)->errorCorrection('H')->generate($qrData);
        Storage::put($fileName, $qrImage);

        $this->orderRepo->update($order->id, ['qr_code' => $fileName]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Ticket booked successfully!');
    }
}
