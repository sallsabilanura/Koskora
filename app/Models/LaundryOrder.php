<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'laundry_id',
        'items',
        'total_price',
        'status',
        'notes',
        'payment_status',
        'payment_proof',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function review()
    {
        return $this->hasOne(LaundryReview::class, 'order_id');
    }
}
