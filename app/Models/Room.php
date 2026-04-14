<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    protected $casts = [
        'picture' => 'array',
    ];

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
