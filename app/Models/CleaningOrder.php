<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cleaner_id',
        'package_id',
        'scheduled_at',
        'status',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cleaner()
    {
        return $this->belongsTo(Cleaner::class);
    }

    public function package()
    {
        return $this->belongsTo(CleaningPackage::class, 'package_id');
    }
}
