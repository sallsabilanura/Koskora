<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'title',
        'description',
        'incident_date',
        'status',
        'attachment',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
