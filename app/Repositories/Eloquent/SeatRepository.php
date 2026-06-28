<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class SeatRepository extends BaseRepository implements SeatRepositoryInterface
{
    public function __construct(Seat $model)
    {
        parent::__construct($model);
    }

    public function getSeatsByStudio($studioId)
    {
        return $this->model->where('studio_id', $studioId)->get();
    }

    public function getBookedSeatIdsForShowtime($showtimeId)
    {
        return DB::table('order_seat')
            ->join('orders', 'order_seat.order_id', '=', 'orders.id')
            ->where('orders.showtime_id', $showtimeId)
            ->pluck('order_seat.seat_id')
            ->toArray();
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
