<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Models\Showtime;
use Carbon\Carbon;

class ShowtimeRepository extends BaseRepository implements ShowtimeRepositoryInterface
{
    public function __construct(Showtime $model)
    {
        parent::__construct($model);
    }

    public function getShowtimesByMovieId($movieId)
    {
        return $this->model->where('movie_id', $movieId)->get();
    }

    public function getShowtimesForNextDays($movieId, $days)
    {
        $dates = collect();
        $showtimesByDate = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            $times = $this->model->with(['studio', 'studio.studioType'])
                ->where('movie_id', $movieId)
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
            
            $groupedByTypeAndStudio = [];
            foreach ($timesByType as $type => $typeShowtimes) {
                $groupedByTypeAndStudio[$type] = $typeShowtimes->groupBy('studio_id');
            }

            $showtimesByDate[$dateString] = collect($groupedByTypeAndStudio);
            
            $dayShort = $date->format('D');
            $dayName = $i == 0 ? __('Today') : __($dayShort);

            $dates->push((object)[
                'date' => $dateString,
                'day_name' => $dayName,
                'day_number' => $date->format('d'),
                'has_showtimes' => $times->count() > 0,
                'month_year' => $date->format('M Y')
            ]);
        }

        return ['dates' => $dates, 'showtimesByDate' => $showtimesByDate];
    }

    public function findWithRelations($id)
    {
        return $this->model->with(['movie', 'studio'])->findOrFail($id);
    }
    
    public function getShowtimesDatatable()
    {
        return $this->model->with(['movie', 'studio'])->select('showtimes.*');
    }
    
    public function checkOverlap($studioId, $startTime, $endTime, $excludeId = null)
    {
        // Define a loose time window (e.g., +/- 12 hours) to fetch relevant showtimes
        $startWindow = (clone $startTime)->subHours(12);
        $endWindow = (clone $endTime)->addHours(12);

        $query = $this->model->where('studio_id', $studioId)
            ->where('start_time', '>=', $startWindow)
            ->where('start_time', '<=', $endWindow);
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $existingShowtimes = $query->get();
        
        // Add 10 minutes overhead to the new showtime's end time (cleaning/exiting)
        $newStart = clone $startTime;
        $newEnd = (clone $endTime)->addMinutes(10);

        foreach ($existingShowtimes as $showtime) {
            $existStart = Carbon::parse($showtime->start_time);
            // Add 10 minutes overhead to the existing showtime's end time (cleaning/exiting)
            $existEnd = Carbon::parse($showtime->end_time)->addMinutes(20);
            
            // Check for overlap
            if ($newStart < $existEnd && $newEnd > $existStart) {
                return true; // Overlap detected
            }
        }
        
        return false;
    }
}
