<?php

namespace App\Services;

use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Carbon\Carbon;
use Exception;

class ShowtimeService
{
    protected $showtimeRepo;
    protected $movieRepo;

    public function __construct(
        ShowtimeRepositoryInterface $showtimeRepo,
        MovieRepositoryInterface $movieRepo
    ) {
        $this->showtimeRepo = $showtimeRepo;
        $this->movieRepo = $movieRepo;
    }

    public function createShowtime(array $data)
    {
        $startTime = Carbon::parse($data['start_time'], 'Asia/Jakarta')->setTimezone('UTC');

        // Validation: must be at least the start of the next hour
        $minTime = now()->addHour()->startOfHour();
        if ($startTime < $minTime) {
            throw new Exception('Showtime must be scheduled for '.$minTime->copy()->timezone('Asia/Jakarta')->format('d M Y, H:i').' onwards.');
        }

        $movie = $this->movieRepo->find($data['movie_id']);
        // Add 10 minutes overhead for ads
        $endTime = $startTime->copy()->addMinutes($movie->duration)->addMinutes(10);

        // Validation: check for overlap in the same studio
        $overlap = $this->showtimeRepo->checkOverlap($data['studio_id'], $startTime, $endTime);

        if ($overlap) {
            throw new Exception('The selected studio is already booked for this time period.');
        }

        return $this->showtimeRepo->create([
            'movie_id' => $data['movie_id'],
            'studio_id' => $data['studio_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    public function updateShowtime(string $id, array $data)
    {
        $showtime = $this->showtimeRepo->find($id);

        $startTime = Carbon::parse($data['start_time'], 'Asia/Jakarta')->setTimezone('UTC');

        // Skip current hour validation on update if the time didn't change, otherwise enforce it
        if ($startTime->format('Y-m-d H:i') !== Carbon::parse($showtime->start_time)->format('Y-m-d H:i')) {
            $minTime = now()->addHour()->startOfHour();
            if ($startTime < $minTime) {
                throw new Exception('Showtime must be scheduled for '.$minTime->copy()->timezone('Asia/Jakarta')->format('d M Y, H:i').' onwards.');
            }
        }

        $movie = $this->movieRepo->find($data['movie_id']);
        // Add 10 minutes overhead for ads
        $endTime = $startTime->copy()->addMinutes($movie->duration)->addMinutes(10);

        // Validation: check for overlap in the same studio, excluding this specific showtime
        $overlap = $this->showtimeRepo->checkOverlap($data['studio_id'], $startTime, $endTime, $id);

        if ($overlap) {
            throw new Exception('The selected studio is already booked for this time period.');
        }

        return $this->showtimeRepo->update($id, [
            'movie_id' => $data['movie_id'],
            'studio_id' => $data['studio_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}
