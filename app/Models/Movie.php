<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasUuids;

    public function newUniqueId()
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }
    protected $fillable = ['title', 'description', 'poster', 'duration', 'age_rating'];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
