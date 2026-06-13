<?php

namespace Database\Seeders;

use App\Models\UnitsBooking;
use Illuminate\Database\Seeder;

class UnitBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitsBooking::insert([
            // PS4 Reguler Lantai 1
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 1', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 2', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 3', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 4', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 5', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 6', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 7', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 8', 'created_at' => now(), 'updated_at' => now()],

            // PS5 Reguler Lantai 1
            ['jenis_unit_id' => '3', 'kode_unit' => 'Unit 9', 'created_at' => now(), 'updated_at' => now()],

            // PS5 VIP Lantai 1
            ['jenis_unit_id' => '4', 'kode_unit' => 'VIP', 'created_at' => now(), 'updated_at' => now()],

            // PS4 Reguler Lantai 2
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 11', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 12', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 13', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 14', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 15', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'kode_unit' => 'Unit 16', 'created_at' => now(), 'updated_at' => now()],

            // PS4 Pro Lantai 2
            ['jenis_unit_id' => '2', 'kode_unit' => 'Unit 17', 'created_at' => now(), 'updated_at' => now()],

            // PS5 Reguler Lantai 2
            ['jenis_unit_id' => '3', 'kode_unit' => 'Unit 18', 'created_at' => now(), 'updated_at' => now()],

            // PS5 VVIP Lantai 2
            ['jenis_unit_id' => '5', 'kode_unit' => 'VVIP 1', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'kode_unit' => 'VVIP 2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
