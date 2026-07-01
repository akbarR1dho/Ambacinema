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
    
    public function getMoviesDatatable(array $filters = [])
    {
        $query = $this->model->select('movies.*');

        if (!empty($filters['search'])) {
            $query->whereRaw('LOWER(title) like ?', ['%' . strtolower($filters['search']) . '%']);
        }

        if (!empty($filters['age_rating'])) {
            $query->where('age_rating', $filters['age_rating']);
        }

        return $query;
    }
}
