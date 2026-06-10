<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'payment_proof',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
