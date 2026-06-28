<?php

namespace App\Repositories\Interfaces;

interface SeatRepositoryInterface extends BaseRepositoryInterface
{
    public function getSeatsByStudio($studioId);
    public function getBookedSeatIdsForShowtime($showtimeId);
    public function getSeatNamesByIds(array $seatIds);
    public function insertSeats(array $seats);
}
