<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveMoviesForToday();
    public function findWithShowtimes($id);
    public function getMoviesDatatable();
}
