<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenants::class , 'tenant_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
