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
        $query = $this->model->where('studio_id', $studioId)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                  });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}
