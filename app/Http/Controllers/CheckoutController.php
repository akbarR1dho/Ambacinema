<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Showtime;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id'
        ]);

        $showtime = Showtime::with(['movie', 'studio'])->findOrFail($request->showtime_id);
        
        // Double check if seats are already booked
        $bookedSeats = \DB::table('order_seat')
            ->join('orders', 'order_seat.order_id', '=', 'orders.id')
            ->where('orders.showtime_id', $showtime->id)
            ->whereIn('order_seat.seat_id', $request->seats)
            ->exists();

        if ($bookedSeats) {
            return back()->withErrors(['seats' => 'One or more selected seats have already been booked. Please try again.']);
        }

        $pricePerSeat = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;
        $totalPrice = count($request->seats) * $pricePerSeat;

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'showtime_id' => $showtime->id,
            'total_price' => $totalPrice,
            'status' => 'confirmed', // Assuming auto confirm for simplicity
        ]);

        // Attach seats
        $order->seats()->attach($request->seats);

        // Fetch seat names for QR Code
        $seatNames = Seat::whereIn('id', $request->seats)->pluck('seat_number')->implode(', ');

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

        $order->update(['qr_code' => $fileName]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Ticket booked successfully!');
    }
}
