<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    protected $orderRepo;
    protected $showtimeRepo;
    protected $paymentService;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ShowtimeRepositoryInterface $showtimeRepo,
        PaymentServiceInterface $paymentService
    ) {
        $this->orderRepo = $orderRepo;
        $this->showtimeRepo = $showtimeRepo;
        $this->paymentService = $paymentService;
    }

    public function processCheckout(array $validatedData, $paymentType, $userId)
    {
        $showtime = $this->showtimeRepo->findWithRelations($validatedData['showtime_id']);
        
        // Double check if seats are already booked
        $bookedSeats = $this->orderRepo->checkSeatsAvailability($showtime->id, $validatedData['seats']);

        if ($bookedSeats) {
            throw new Exception('One or more selected seats have already been booked. Please try again.');
        }

        $pricePerSeat = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;
        $totalPrice = count($validatedData['seats']) * $pricePerSeat;

        return DB::transaction(function () use ($validatedData, $paymentType, $userId, $showtime, $pricePerSeat, $totalPrice) {
            // Create Order
            $order = $this->orderRepo->create([
                'user_id' => $userId,
                'showtime_id' => $showtime->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'pending_at' => now(),
            ]);

            // Attach seats with UUIDs
            $pivotData = [];
            foreach ($validatedData['seats'] as $seatId) {
                $pivotData[$seatId] = ['id' => (string) \Symfony\Component\Uid\Uuid::v7()];
            }
            $this->orderRepo->attachSeats($order, $pivotData);

            // Process Payment with Midtrans
            $response = $this->paymentService->charge($order, $paymentType, $pricePerSeat, count($validatedData['seats']));
            
            $this->orderRepo->update($order->id, [
                'payment_type' => $paymentType,
                'payment_info' => (array) $response
            ]);

            return $order;
        });
    }
}
