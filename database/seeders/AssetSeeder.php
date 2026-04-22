<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            ['name' => 'AC', 'icon' => 'fas fa-snowflake'],
            ['name' => 'TV', 'icon' => 'fas fa-tv'],
            ['name' => 'WiFi', 'icon' => 'fas fa-wifi'],
            ['name' => 'Meja', 'icon' => 'fas fa-table'],
            ['name' => 'Lemari', 'icon' => 'fas fa-closet'],
            ['name' => 'Kasur', 'icon' => 'fas fa-bed'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'fas fa-bath'],
            ['name' => 'Kulkas Mini', 'icon' => 'fas fa-refrigerator'],
        ];

        foreach ($assets as $asset) {
            \App\Models\Asset::firstOrCreate(['name' => $asset['name']], $asset);
        }
    }
}
