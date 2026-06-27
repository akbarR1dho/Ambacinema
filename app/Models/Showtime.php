<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $fillable = ['movie_id', 'studio_id', 'start_time', 'end_time'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}
