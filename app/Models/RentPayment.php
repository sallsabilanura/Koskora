<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    protected $fillable = [
        'rental_id',
        'room_id',
        'tenants_id',
        'month',
        'amount',
        'payment_date',
        'status',
        'method',
        'payment_proof',
        'rejection_reason',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tenants()
    {
        return $this->belongsTo(Tenants::class , 'tenants_id');
    }
}
