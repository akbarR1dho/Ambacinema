<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class SeatRepository extends BaseRepository implements SeatRepositoryInterface
{
    protected $orderRepo;

    public function __construct(Seat $model, OrderRepositoryInterface $orderRepo)
    {
        parent::__construct($model);
        $this->orderRepo = $orderRepo;
    }

    public function getSeatsByStudio($studioId)
    {
        return $this->model->where('studio_id', $studioId)->get();
    }

    public function getSeatHoldStatusesForShowtime($showtimeId): array
    {
        $this->orderRepo->releaseExpiredOrders($showtimeId);

        $seats = DB::table('order_seat')
            ->join('orders', 'order_seat.order_id', '=', 'orders.id')
            ->where('orders.showtime_id', $showtimeId)
            ->whereIn('orders.status', ['confirmed', 'pending'])
            ->select('order_seat.seat_id', 'orders.status')
            ->get();

        $confirmedSeatIds = [];
        $heldSeatIds = [];

        foreach ($seats as $seat) {
            if ($seat->status === 'confirmed') {
                $confirmedSeatIds[] = $seat->seat_id;
            } elseif ($seat->status === 'pending') {
                $heldSeatIds[] = $seat->seat_id;
            }
        }

        $confirmedSeatIds = array_values(array_unique($confirmedSeatIds));
        $heldSeatIds = array_values(array_unique($heldSeatIds));

        return [
            'confirmed' => $confirmedSeatIds,
            'held' => $heldSeatIds,
            'all_unavailable' => array_values(array_unique(array_merge($confirmedSeatIds, $heldSeatIds))),
        ];
    }

    public function getBookedSeatIdsForShowtime($showtimeId)
    {
        $statuses = $this->getSeatHoldStatusesForShowtime($showtimeId);
        return $statuses['all_unavailable'];
    }
    
    public function getSeatNamesByIds(array $seatIds)
    {
        return $this->model->whereIn('id', $seatIds)->pluck('seat_number')->implode(', ');
    }
    
    public function insertSeats(array $seats)
    {
        return $this->model->insert($seats);
    }
}
