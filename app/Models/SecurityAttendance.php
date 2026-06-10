<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'type',
        'location',
        'attendance_time',
        'note',
    ];

    protected $casts = [
        'attendance_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
