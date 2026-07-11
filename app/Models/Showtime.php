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

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function getStartTimeLocalAttribute()
    {
        return $this->start_time ? $this->start_time->timezone('Asia/Jakarta') : null;
    }

    public function getEndTimeLocalAttribute()
    {
        return $this->end_time ? $this->end_time->timezone('Asia/Jakarta') : null;
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}
