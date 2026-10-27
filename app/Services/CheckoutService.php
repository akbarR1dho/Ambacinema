<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Models\Seat;
use App\Models\Order;
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
        
        $pricePerSeat = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;
        $totalPrice = count($validatedData['seats']) * $pricePerSeat;

        return DB::transaction(function () use ($validatedData, $paymentType, $userId, $showtime, $pricePerSeat, $totalPrice) {
            // 1. Release any expired pending holds for this showtime
            $this->orderRepo->releaseExpiredOrders($showtime->id);

            // 2. If the current user already had a pending order for this showtime (e.g. re-selected seats),
            // release the old hold so they do not hold multiple sets of seats concurrently.
            $previousHolds = Order::where('user_id', $userId)
                ->where('showtime_id', $showtime->id)
                ->where('status', 'pending')
                ->get();
            foreach ($previousHolds as $prevOrder) {
                $prevOrder->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                ]);
            }

            // 3. Lock selected seat rows to prevent concurrent bookings
            Seat::whereIn('id', $validatedData['seats'])->lockForUpdate()->get();

            // 4. Double check if any of the selected seats are already confirmed or actively held
            $isUnavailable = $this->orderRepo->checkSeatsAvailability($showtime->id, $validatedData['seats']);

            if ($isUnavailable) {
                throw new Exception(__('One or more selected seats have already been booked or are currently on hold. Please choose different seats.'));
            }

            // 5. Create Order with status 'pending' (Establishes the Temporary Hold)
            $order = $this->orderRepo->create([
                'user_id' => $userId,
                'showtime_id' => $showtime->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'pending_at' => now(),
            ]);

            // 6. Attach seats with UUIDs
            $pivotData = [];
            foreach ($validatedData['seats'] as $seatId) {
                $pivotData[$seatId] = ['id' => (string) \Symfony\Component\Uid\Uuid::v7()];
            }
            $this->orderRepo->attachSeats($order, $pivotData);

            // 7. Process Payment with Midtrans
            try {
                $response = $this->paymentService->charge($order, $paymentType, $pricePerSeat, count($validatedData['seats']));
                
                $this->orderRepo->update($order->id, [
                    'payment_type' => $paymentType,
                    'payment_info' => (array) $response
                ]);
            } catch (Exception $e) {
                // If payment charge creation fails, release hold immediately
                $order->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                ]);
                throw $e;
            }

            return $order;
        });
    }
}
