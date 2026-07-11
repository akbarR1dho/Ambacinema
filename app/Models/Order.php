<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    public function newUniqueId()
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }
    protected $fillable = ['user_id', 'showtime_id', 'total_price', 'status', 'qr_code', 'pending_at', 'confirmed_at', 'failed_at', 'payment_type', 'payment_info'];

    protected $casts = [
        'id' => 'string',
        'pending_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'failed_at' => 'datetime',
        'payment_info' => 'array',
    ];

    public function getPendingAtLocalAttribute()
    {
        return $this->pending_at ? $this->pending_at->timezone('Asia/Jakarta') : null;
    }

    public function getConfirmedAtLocalAttribute()
    {
        return $this->confirmed_at ? $this->confirmed_at->timezone('Asia/Jakarta') : null;
    }

    public function getFailedAtLocalAttribute()
    {
        return $this->failed_at ? $this->failed_at->timezone('Asia/Jakarta') : null;
    }

    public function getCreatedAtLocalAttribute()
    {
        return $this->created_at ? $this->created_at->timezone('Asia/Jakarta') : null;
    }

    public function getExpiryTimeLocalAttribute()
    {
        if (isset($this->payment_info['expiry_time'])) {
            return \Carbon\Carbon::parse($this->payment_info['expiry_time'], 'Asia/Jakarta');
        }
        return $this->created_at_local ? $this->created_at_local->copy()->addHours(24) : null;
    }

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
