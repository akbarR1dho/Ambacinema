<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $movieRepo;
    protected $showtimeRepo;
    protected $seatRepo;

    public function __construct(
        MovieRepositoryInterface $movieRepo,
        ShowtimeRepositoryInterface $showtimeRepo,
        SeatRepositoryInterface $seatRepo
    ) {
        $this->movieRepo = $movieRepo;
        $this->showtimeRepo = $showtimeRepo;
        $this->seatRepo = $seatRepo;
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
            return redirect()->route('home')->with('error', 'This showtime has already passed.');
        }

        $allSeats = $this->seatRepo->getSeatsByStudio($showtime->studio_id);
        
        // Get booked seats for this showtime
        $bookedSeatIds = $this->seatRepo->getBookedSeatIdsForShowtime($id);

        // Price is dynamically determined by the studio type and showtime date
        $price = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;

        return view('booking.seat', compact('showtime', 'allSeats', 'bookedSeatIds', 'price'));
    }
}
