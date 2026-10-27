<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    public const HOLD_DURATION_MINUTES = 5;

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

    public function getExpiryTimeUtcAttribute()
    {
        if (isset($this->payment_info['expiry_time'])) {
            try {
                return \Carbon\Carbon::parse($this->payment_info['expiry_time'], 'Asia/Jakarta')->setTimezone('UTC');
            } catch (\Exception $e) {
                // Ignore and fall back to calculation below
            }
        }
        $time = $this->pending_at ?? $this->created_at;
        return $time ? $time->copy()->addMinutes(self::HOLD_DURATION_MINUTES) : null;
    }

    public function getExpiryTimeLocalAttribute()
    {
        $utc = $this->expiry_time_utc;
        return $utc ? $utc->copy()->setTimezone('Asia/Jakarta') : null;
    }

    public function isExpired(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        $expiry = $this->expiry_time_utc;
        return $expiry ? now()->greaterThan($expiry) : false;
    }

    public function getRemainingHoldSecondsAttribute(): int
    {
        if ($this->status !== 'pending') {
            return 0;
        }
        $expiry = $this->expiry_time_utc;
        if (!$expiry) {
            return 0;
        }
        $diff = now()->diffInSeconds($expiry, false);
        return max(0, (int) $diff);
    }

    public function markAsFailed(string $reason = 'expired'): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
        ]);
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
