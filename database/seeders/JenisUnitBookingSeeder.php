<?php

namespace Database\Seeders;

use App\Models\JenisUnitBooking;
use Illuminate\Database\Seeder;

class JenisUnitBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisUnitBooking::insert([
            ['tipe' => 'PS4', 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS4 Pro', 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS5', 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS5 VIP', 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS5 VVIP', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
