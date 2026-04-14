<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'user_id',
        'order_id',
        'rating',
        'comment',
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(LaundryOrder::class, 'order_id');
    }
}
