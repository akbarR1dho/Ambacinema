<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketService
{
    /**
     * Generate QR Code for an order
     */
    public function generateQrCode($order)
    {
        if ($order->qr_code) return;
        
        $order->loadMissing(['user', 'showtime.movie', 'showtime.studio', 'seats']);
        
        $seatNames = $order->seats->pluck('seat_number')->implode(', ');

        $qrData = json_encode([
            'Order ID' => $order->id,
            'User' => $order->user->name,
            'Movie' => $order->showtime->movie->title,
            'Studio' => $order->showtime->studio->name,
            'Time' => \Carbon\Carbon::parse($order->showtime->start_time)->format('Y-m-d H:i'),
            'Seats' => $seatNames
        ]);

        $fileName = 'qrcodes/order_' . $order->id . '_' . Str::random(10) . '.svg';
        
        if (!Storage::exists('qrcodes')) {
            Storage::makeDirectory('qrcodes');
        }

        $qrCode = QrCode::format('svg')
                    ->size(300)
                    ->color(15, 23, 42)
                    ->generate($qrData);
                    
        Storage::put($fileName, $qrCode);
        
        $order->update([
            'qr_code' => $fileName
        ]);
    }
}
