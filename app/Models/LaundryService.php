<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryService extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'item_name',
        'price',
    ];

    public function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }
}
