<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'room_number',
        'room_type',
        'price',
        'status',
        'address',
        'province',
        'city',
        'district',
        'village',
        'description',
        'picture',
        'gender',
        'discount_percentage',
        'discount_label',
        'discount_start',
        'discount_end',
    ];

    protected $casts = [
        'picture' => 'array',
        'discount_start' => 'date',
        'discount_end' => 'date',
    ];

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'room_asset');
    }

    public function reviews()
    {
        return $this->hasMany(RoomReview::class);
    }

    public function hasActiveDiscount()
    {
        if ($this->discount_percentage <= 0) {
            return false;
        }

        $today = now()->startOfDay();

        // If no dates set, discount is always active (legacy behavior)
        if (!$this->discount_start && !$this->discount_end) {
            return true;
        }

        // Check date range
        $start = $this->discount_start ? $this->discount_start->startOfDay() : null;
        $end = $this->discount_end ? $this->discount_end->endOfDay() : null;

        if ($start && $today->lt($start)) {
            return false;
        }

        if ($end && $today->gt($end)) {
            return false;
        }

        return true;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->hasActiveDiscount()) {
            return $this->price * (1 - ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    public function hasDiscount()
    {
        return $this->hasActiveDiscount();
    }
}
