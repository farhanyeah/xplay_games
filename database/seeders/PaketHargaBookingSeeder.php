<?php

namespace Database\Seeders;

use App\Models\PaketHargaBooking;
use Illuminate\Database\Seeder;

class PaketHargaBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketHargaBooking::insert([
            // PS4 (jenis_unit_id: 1)
            ['jenis_unit_id' => '1', 'jumlah_jam' => '1', 'harga' => 12000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '2', 'harga' => 23000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '3', 'harga' => 33000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '4', 'harga' => 43000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '5', 'harga' => 53000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '6', 'harga' => 62000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '7', 'harga' => 71000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '1', 'jumlah_jam' => '8', 'harga' => 80000, 'created_at' => now(), 'updated_at' => now()],

            // PS4 Pro (jenis_unit_id: 2)
            ['jenis_unit_id' => '2', 'jumlah_jam' => '1', 'harga' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '2', 'harga' => 29000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '3', 'harga' => 42000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '4', 'harga' => 55000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '5', 'harga' => 67000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '6', 'harga' => 78000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '7', 'harga' => 89000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '2', 'jumlah_jam' => '8', 'harga' => 10000, 'created_at' => now(), 'updated_at' => now()],
            
            // PS5 (jenis_unit_id: 3)
            ['jenis_unit_id' => '3', 'jumlah_jam' => '1', 'harga' => 20000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '2', 'harga' => 39000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '3', 'harga' => 58000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '4', 'harga' => 75000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '5', 'harga' => 92000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '6', 'harga' => 110000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '7', 'harga' => 125000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '3', 'jumlah_jam' => '8', 'harga' => 140000, 'created_at' => now(), 'updated_at' => now()],

            // PS5 VIP (jenis_unit_id: 4)
            ['jenis_unit_id' => '4', 'jumlah_jam' => '1', 'harga' => 45000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '2', 'harga' => 85000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '3', 'harga' => 120000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '4', 'harga' => 160000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '5', 'harga' => 195000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '6', 'harga' => 230000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '7', 'harga' => 260000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '4', 'jumlah_jam' => '8', 'harga' => 290000, 'created_at' => now(), 'updated_at' => now()],

            // PS5 VVIP (jenis_unit_id: 5)
            ['jenis_unit_id' => '5', 'jumlah_jam' => '1', 'harga' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '2', 'harga' => 90000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '3', 'harga' => 130000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '4', 'harga' => 170000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '5', 'harga' => 210000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '6', 'harga' => 250000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '7', 'harga' => 290000, 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => '5', 'jumlah_jam' => '8', 'harga' => 320000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}