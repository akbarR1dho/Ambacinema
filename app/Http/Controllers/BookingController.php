<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $movieRepo;
    protected $showtimeRepo;
    protected $seatRepo;
    protected $orderRepo;

    public function __construct(
        MovieRepositoryInterface $movieRepo,
        ShowtimeRepositoryInterface $showtimeRepo,
        SeatRepositoryInterface $seatRepo,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->movieRepo = $movieRepo;
        $this->showtimeRepo = $showtimeRepo;
        $this->seatRepo = $seatRepo;
        $this->orderRepo = $orderRepo;
    }

    public function showMovie($id)
    {
        $movie = $this->movieRepo->find($id);
        
        $showtimeData = $this->showtimeRepo->getShowtimesForNextDays($id, 5);
        $dates = $showtimeData['dates'];
        $showtimesByDate = $showtimeData['showtimesByDate'];

        return view('booking.movie', compact('movie', 'dates', 'showtimesByDate'));
    }

    public function selectSeat($id)
    {
        $showtime = $this->showtimeRepo->findWithRelations($id);
        
        if (Carbon::parse($showtime->start_time) < now()) {
            return redirect()->route('home')->with('error', __('This showtime has already passed.'));
        }

        $allSeats = $this->seatRepo->getSeatsByStudio($showtime->studio_id);
        
        // Categorized seat statuses (confirmed vs temporary hold)
        $seatStatuses = $this->seatRepo->getSeatHoldStatusesForShowtime($id);
        $confirmedSeatIds = $seatStatuses['confirmed'];
        $heldSeatIds = $seatStatuses['held'];
        $bookedSeatIds = $seatStatuses['all_unavailable'];

        // Check if current user has an active pending hold for this showtime
        $userActivePendingOrder = $this->orderRepo->getUserPendingOrderForShowtime(Auth::id(), $id);

        // Price is dynamically determined by the studio type and showtime date
        $price = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;

        return view('booking.seat', compact(
            'showtime', 
            'allSeats', 
            'confirmedSeatIds', 
            'heldSeatIds', 
            'bookedSeatIds', 
            'price',
            'userActivePendingOrder'
        ));
    }

    public function getSeatsStatus($id)
    {
        $seatStatuses = $this->seatRepo->getSeatHoldStatusesForShowtime($id);
        $userActivePendingOrder = $this->orderRepo->getUserPendingOrderForShowtime(Auth::id(), $id);

        return response()->json([
            'status' => 'success',
            'confirmed' => $seatStatuses['confirmed'],
            'held' => $seatStatuses['held'],
            'unavailable' => $seatStatuses['all_unavailable'],
            'user_pending_order_id' => $userActivePendingOrder ? $userActivePendingOrder->id : null,
            'user_held_seats' => $userActivePendingOrder ? $userActivePendingOrder->seats->pluck('id')->toArray() : [],
            'remaining_seconds' => $userActivePendingOrder ? $userActivePendingOrder->remaining_hold_seconds : 0,
        ]);
    }
}
