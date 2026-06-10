<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (akses global, tidak terikat daerah)
        \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@koskora.com'],
            [
                'name' => 'Super Admin KosKora',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'superadmin',
                'district' => null,
                'email_verified_at' => now(),
            ]
        );

        // Admin Wilayah Pasar Minggu
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@koskora.com'],
            [
                'name' => 'Admin KosKora',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'district' => 'Pasar Minggu',
                'email_verified_at' => now(),
            ]
        );

        // Admin Wilayah Cilandak (contoh admin daerah lain)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin.cilandak@koskora.com'],
            [
                'name' => 'Admin KosKora Cilandak',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'district' => 'Cilandak',
                'email_verified_at' => now(),
            ]
        );

        // User penyewa biasa
        \App\Models\User::updateOrCreate(
            ['email' => 'user@koskora.com'],
            [
                'name' => 'Regular User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
