<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StudioType extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId()
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

    protected $fillable = ['name', 'price_weekday', 'price_friday', 'price_weekend', 'description'];

    public function studios()
    {
        return $this->hasMany(Studio::class);
    }

    public function getPriceForDate($date)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        
        if ($carbonDate->isFriday()) {
            return $this->price_friday;
        } elseif ($carbonDate->isWeekend()) {
            return $this->price_weekend;
        }
        
        return $this->price_weekday;
    }
}
