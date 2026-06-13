<?php

namespace Database\Seeders;

use App\Models\PaketHargaSewa;
use Illuminate\Database\Seeder;

class PaketHargaSewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketHargaSewa::insert([
            // PS4 (jenis_unit_id: 1)
            ['jenis_unit_id' => 1, 'durasi_hari' => 1, 'harga' => 150000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 1, 'durasi_hari' => 3, 'harga' => 400000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 1, 'durasi_hari' => 7, 'harga' => 800000, 'created_at' => now(), 'updated_at' => now()],

            // PS4 Pro (jenis_unit_id: 2)
            ['jenis_unit_id' => 2, 'durasi_hari' => 1, 'harga' => 175000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 2, 'durasi_hari' => 3, 'harga' => 450000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 2, 'durasi_hari' => 7, 'harga' => 900000, 'created_at' => now(), 'updated_at' => now()],

            // PS5 (jenis_unit_id: 3)
            ['jenis_unit_id' => 3, 'durasi_hari' => 1, 'harga' => 250000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 3, 'durasi_hari' => 3, 'harga' => 650000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 3, 'durasi_hari' => 7, 'harga' => 1350000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
