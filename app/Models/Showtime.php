<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Showtime extends Model
{
    use HasUuids;

    public function newUniqueId()
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }
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
