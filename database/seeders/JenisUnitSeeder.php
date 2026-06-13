<?php

namespace Database\Seeders;

use App\Models\JenisUnit;
use Illuminate\Database\Seeder;

class JenisUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisUnit::insert([
            ['tipe' => 'PS4', 'harga_jaminan' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS4 Pro', 'harga_jaminan' => 60000, 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'PS5', 'harga_jaminan' => 100000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
