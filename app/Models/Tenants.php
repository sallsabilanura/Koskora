<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nama_panggilan',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nomor_whatsapp',
        'alamat_ktp',
        'address',
        'rt',
        'rw',
        'province',
        'city',
        'district',
        'village',
        'occupation',
        'emergency_contact',
        'foto_ktp',
        'foto_diri',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'tenant_id');
    }

    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class, 'tenants_id');
    }
}
