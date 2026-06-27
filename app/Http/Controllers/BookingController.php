<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Seat;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function showMovie($id)
    {
        $movie = Movie::findOrFail($id);
        
        $dates = collect();
        $showtimesByDate = [];

        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::today()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            $times = Showtime::with(['studio', 'studio.studioType'])
                ->where('movie_id', $id)
                ->whereDate('start_time', $dateString)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->get();
                
            $timesByType = [];
            foreach ($times as $st) {
                $typeName = $st->studio && $st->studio->studioType ? $st->studio->studioType->name : 'Regular';
                if (!isset($timesByType[$typeName])) {
                    $timesByType[$typeName] = collect();
                }
                $timesByType[$typeName]->push($st);
            }
            
            // Group by studio_id inside each type
            $groupedByTypeAndStudio = [];
            foreach ($timesByType as $type => $typeShowtimes) {
                $groupedByTypeAndStudio[$type] = $typeShowtimes->groupBy('studio_id');
            }

            $showtimesByDate[$dateString] = collect($groupedByTypeAndStudio);
            
            $englishDay = $date->format('l');
            $indoDays = [
                'Sunday' => 'Min', 'Monday' => 'Sen', 'Tuesday' => 'Sel',
                'Wednesday' => 'Rab', 'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab'
            ];
            $dayName = $i == 0 ? 'Hari ini' : $indoDays[$englishDay];

            $dates->push((object)[
                'date' => $dateString,
                'day_name' => $dayName,
                'day_number' => $date->format('d'),
                'has_showtimes' => $times->count() > 0,
                'month_year' => $date->format('M Y')
            ]);
        }

        return view('booking.movie', compact('movie', 'dates', 'showtimesByDate'));
    }

    public function selectSeat($id)
    {
        $showtime = Showtime::with(['movie', 'studio'])->findOrFail($id);
        
        if (Carbon::parse($showtime->start_time) < now()) {
            return redirect()->route('home')->with('error', 'This showtime has already passed.');
        }

        $allSeats = Seat::where('studio_id', $showtime->studio_id)->get();
        
        // Get booked seats for this showtime
        $bookedSeatIds = \DB::table('order_seat')
            ->join('orders', 'order_seat.order_id', '=', 'orders.id')
            ->where('orders.showtime_id', $id)
            ->pluck('order_seat.seat_id')
            ->toArray();

        // Price is dynamically determined by the studio type and showtime date
        $price = $showtime->studio->studioType ? $showtime->studio->studioType->getPriceForDate($showtime->start_time) : 50000;

        return view('booking.seat', compact('showtime', 'allSeats', 'bookedSeatIds', 'price'));
    }
}
