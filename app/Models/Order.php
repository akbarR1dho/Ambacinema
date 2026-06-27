<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'showtime_id', 'total_price', 'status', 'qr_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }
}
