<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    public function __construct(Movie $model)
    {
        parent::__construct($model);
    }

    public function getActiveMoviesForToday()
    {
        return $this->model->whereHas('showtimes', function ($query) {
            $query->whereDate('start_time', now()->toDateString())
                  ->where('start_time', '>', now());
        })->latest()->get();
    }

    public function findWithShowtimes($id)
    {
        return $this->model->with('showtimes')->findOrFail($id);
    }
    
    public function getMoviesDatatable()
    {
        return $this->model->select('movies.*');
    }
}
