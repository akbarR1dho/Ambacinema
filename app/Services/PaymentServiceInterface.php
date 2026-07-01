<?php

namespace App\Services;

use App\Models\Order;

interface PaymentServiceInterface
{
    /**
     * Charge a payment for an order
     *
     * @param Order $order
     * @param string $paymentType
     * @param float $pricePerSeat
     * @param int $quantity
     * @return array The payment info response
     */
    public function charge(Order $order, string $paymentType, float $pricePerSeat, int $quantity): array;

    /**
     * Check the current status of a payment
     *
     * @param Order $order
     * @return array Returns an array with the 'status' key
     */
    public function checkStatus(Order $order): array;

    /**
     * Process an incoming webhook/notification from the payment gateway
     *
     * @param mixed $notification
     * @return void
     */
    public function handleNotification($notification): void;
}
