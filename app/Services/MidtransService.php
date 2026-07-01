<?php

namespace App\Services;

use App\Models\Order;
use Exception;

class MidtransService implements PaymentServiceInterface
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        $this->configureMidtrans();
    }

    /**
     * Set up Midtrans configuration
     */
    protected function configureMidtrans(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized') ?? true;
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds') ?? true;
        
        if (!app()->environment('production')) {
            \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
            \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = 0;
            \Midtrans\Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
        }
    }

    public function charge(Order $order, string $paymentType, float $pricePerSeat, int $quantity): array
    {
        $params = [
            'payment_type' => $paymentType === 'bca_va' ? 'bank_transfer' : ($paymentType === 'echannel' ? 'echannel' : 'gopay'),
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Customer',
                'email' => $order->user->email ?? 'customer@example.com',
            ],
            'item_details' => [
                [
                    'id' => $order->showtime->id,
                    'price' => $pricePerSeat,
                    'quantity' => $quantity,
                    'name' => 'Ticket ' . $order->showtime->movie->title,
                ]
            ],
            'custom_expiry' => [
                'order_time' => date('Y-m-d H:i:s O'),
                'expiry_duration' => 5,
                'unit' => 'minute'
            ]
        ];

        if ($paymentType === 'bca_va') {
            $params['bank_transfer'] = ['bank' => 'bca'];
        } elseif ($paymentType === 'echannel') {
            $params['echannel'] = [
                'bill_info1' => 'Payment for:',
                'bill_info2' => 'Ambacinema Tickets'
            ];
        }

        $response = \Midtrans\CoreApi::charge($params);
        return (array) $response;
    }

    public function checkStatus(Order $order): array
    {
        try {
            $status = \Midtrans\Transaction::status($order->id);
            $this->updateOrderStatus($order, $status->transaction_status, $status->payment_type, $status->fraud_status ?? '');
            
            return ['status' => $order->status];
        } catch (Exception $e) {
            // Silently ignore Midtrans API error for polling, just return current DB status
            return ['status' => $order->status];
        }
    }

    public function handleNotification($notificationPayload): void
    {
        // Extract order_id directly from the payload array passed by Laravel Request.
        // We do this because 'php://input' can be empty in serverless environments (like Vercel)
        $orderId = $notificationPayload['order_id'] ?? null;

        if (!$orderId) {
            return;
        }

        $order = Order::find($orderId);

        if ($order) {
            // Verify signature key to prevent spoofing
            $statusCode = $notificationPayload['status_code'] ?? '';
            $grossAmount = $notificationPayload['gross_amount'] ?? '';
            $serverKey = config('midtrans.server_key');
            $signatureKey = $notificationPayload['signature_key'] ?? '';

            // Midtrans signature rule: SHA512(order_id + status_code + gross_amount + server_key)
            $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

            if ($calculatedSignature === $signatureKey) {
                $transaction = $notificationPayload['transaction_status'] ?? '';
                $type = $notificationPayload['payment_type'] ?? '';
                $fraud = $notificationPayload['fraud_status'] ?? '';

                $this->updateOrderStatus($order, $transaction, $type, $fraud);
            }
        }
    }

    protected function updateOrderStatus(Order $order, string $transaction, string $type, string $fraud = ''): void
    {
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->update(['status' => 'pending']);
                } else {
                    $order->update(['status' => 'confirmed', 'confirmed_at' => now()]);
                    $this->ticketService->generateQrCode($order);
                }
            }
        } else if ($transaction == 'settlement') {
            $order->update(['status' => 'confirmed', 'confirmed_at' => now()]);
            $this->ticketService->generateQrCode($order);
        } else if ($transaction == 'pending') {
            $order->update(['status' => 'pending']);
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->update(['status' => 'failed', 'failed_at' => now()]);
        }
    }
}
