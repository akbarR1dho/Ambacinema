<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getOrdersForUserWithCursor($userId, $filter, $perPage)
    {
        $query = $this->model->with(['showtime.movie', 'showtime.studio', 'seats'])
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($filter !== 'all') {
            $now = Carbon::now('Asia/Jakarta');
            
            if ($filter === 'today') {
                $query->where('created_at', '>=', $now->copy()->startOfDay()->setTimezone('UTC'))
                      ->where('created_at', '<=', $now->copy()->endOfDay()->setTimezone('UTC'));
            } elseif ($filter === 'weekly') {
                $query->whereBetween('created_at', [
                    $now->copy()->startOfWeek()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfWeek()->setTimezone('UTC')->toDateTimeString()
                ]);
            } elseif ($filter === 'monthly') {
                $query->whereBetween('created_at', [
                    $now->copy()->startOfMonth()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfMonth()->setTimezone('UTC')->toDateTimeString()
                ]);
            } elseif ($filter === 'annual') {
                $query->whereBetween('created_at', [
                    $now->copy()->startOfYear()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfYear()->setTimezone('UTC')->toDateTimeString()
                ]);
            }
        }

        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')->cursorPaginate($perPage);
    }

    public function checkSeatsAvailability($showtimeId, array $seatIds)
    {
        return DB::table('order_seat')
            ->join('orders', 'order_seat.order_id', '=', 'orders.id')
            ->where('orders.showtime_id', $showtimeId)
            ->whereIn('order_seat.seat_id', $seatIds)
            ->exists();
    }
    
    public function attachSeats($order, array $pivotData)
    {
        return $order->seats()->attach($pivotData);
    }
    
    public function getOrdersDatatable(array $filters = [])
    {
        $query = $this->model->with(['user', 'showtime.movie', 'showtime.studio'])->select('orders.*');
        
        if (!empty($filters['studio_id']) || !empty($filters['movie_id'])) {
            $query->whereHas('showtime', function($q) use ($filters) {
                if (!empty($filters['studio_id'])) $q->where('studio_id', $filters['studio_id']);
                if (!empty($filters['movie_id'])) $q->where('movie_id', $filters['movie_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    public function getFilteredOrders(array $filters)
    {
        return $this->getOrdersDatatable($filters)->get();
    }
    
    public function getChartData($filters)
    {
        $query = $this->model->query()
            ->join('showtimes', 'orders.showtime_id', '=', 'showtimes.id')
            ->selectRaw('DATE(orders.created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Jakarta\') as date, SUM(orders.total_price) as revenue')
            ->where('orders.status', 'confirmed');

        if (!empty($filters['date_filter'])) {
            $filter = $filters['date_filter'];
            $now = Carbon::now('Asia/Jakarta');
            
            if ($filter === 'today') {
                $query->where('orders.created_at', '>=', $now->copy()->startOfDay()->setTimezone('UTC'))
                      ->where('orders.created_at', '<=', $now->copy()->endOfDay()->setTimezone('UTC'));
            } elseif ($filter === 'weekly') {
                $query->whereBetween('orders.created_at', [
                    $now->copy()->startOfWeek()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfWeek()->setTimezone('UTC')->toDateTimeString()
                ]);
            } elseif ($filter === 'monthly') {
                $query->whereBetween('orders.created_at', [
                    $now->copy()->startOfMonth()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfMonth()->setTimezone('UTC')->toDateTimeString()
                ]);
            } elseif ($filter === 'annual') {
                $query->whereBetween('orders.created_at', [
                    $now->copy()->startOfYear()->setTimezone('UTC')->toDateTimeString(),
                    $now->copy()->endOfYear()->setTimezone('UTC')->toDateTimeString()
                ]);
            }
        }

        if (!empty($filters['studio_id'])) {
            $query->where('showtimes.studio_id', $filters['studio_id']);
        }

        if (!empty($filters['movie_id'])) {
            $query->where('showtimes.movie_id', $filters['movie_id']);
        }

        return $query->groupBy('date')->orderBy('date', 'ASC')->get();
    }
    
    public function findUserOrderWithRelations($id, $userId)
    {
        return $this->model->with(['showtime.movie', 'showtime.studio', 'seats'])
            ->where('user_id', $userId)
            ->findOrFail($id);
    }
    
    public function findAdminOrderWithRelations($id)
    {
        return $this->model->with(['user', 'showtime.movie', 'showtime.studio', 'seats'])
            ->findOrFail($id);
    }
}
