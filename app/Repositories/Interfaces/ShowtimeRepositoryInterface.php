<?php

namespace App\Repositories\Interfaces;

interface ShowtimeRepositoryInterface extends BaseRepositoryInterface
{
    public function getShowtimesByMovieId($movieId);
    public function getShowtimesForNextDays($movieId, $days);
    public function findWithRelations($id);
    public function getShowtimesDatatable();
    public function checkOverlap($studioId, $startTime, $endTime, $excludeId = null);
}
