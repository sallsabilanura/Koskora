<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_asset');
    }
}
