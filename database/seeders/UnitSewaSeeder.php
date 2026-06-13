<?php

namespace Database\Seeders;

use App\Models\UnitSewa;
use Illuminate\Database\Seeder;

class UnitSewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitSewa::insert([
            ['jenis_unit_id' => '1', 'kode_unit' => 'PS4-01', 'status' => 'available', 
            'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'kode_unit' => 'PS4P-01', 'status' => 'available', 
            'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'kode_unit' => 'PS5-01', 'status' => 'available', 
            'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
