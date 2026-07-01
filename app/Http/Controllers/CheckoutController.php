<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Booking\CheckoutRequest;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Services\PaymentServiceInterface;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $orderRepo;
    protected $showtimeRepo;
    protected $seatRepo;
    protected $paymentService;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ShowtimeRepositoryInterface $showtimeRepo,
        SeatRepositoryInterface $seatRepo,
        PaymentServiceInterface $paymentService
    ) {
        $this->orderRepo = $orderRepo;
        $this->showtimeRepo = $showtimeRepo;
        $this->seatRepo = $seatRepo;
        $this->paymentService = $paymentService;
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
            'status' => 'pending',
            'pending_at' => now(),
        ]);

        // Attach seats with UUIDs
        $pivotData = [];
        foreach ($request->seats as $seatId) {
            $pivotData[$seatId] = ['id' => (string) \Symfony\Component\Uid\Uuid::v7()];
        }
        $this->orderRepo->attachSeats($order, $pivotData);

        // Process Payment with Midtrans
        $paymentType = $request->input('payment_type');
        
        try {
            $response = $this->paymentService->charge($order, $paymentType, $pricePerSeat, count($request->seats));
            
            $this->orderRepo->update($order->id, [
                'payment_type' => $paymentType,
                'payment_info' => (array) $response
            ]);

            return redirect()->route('orders.pay', $order->id)->with('success', 'Order created successfully. Please complete your payment.');
        } catch (\Exception $e) {
            return back()->withErrors(['seats' => 'Failed to generate payment: ' . $e->getMessage()]);
        }
    }
}
