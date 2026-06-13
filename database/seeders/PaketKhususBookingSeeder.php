<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketKhususBooking;

class PaketKhususBookingSeeder extends Seeder
{
    public function run(): void
    {
        PaketKhususBooking::insert([
            // Happy Hour — Senin-Jumat, 10:00-16:00, 3 jam
            ['jenis_unit_id' => 1, 'nama_paket' => 'Happy Hour', 'jumlah_jam' => 3, 'harga' => 27000, 'hari_berlaku' => json_encode([1,2,3,4,5]), 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 2, 'nama_paket' => 'Happy Hour', 'jumlah_jam' => 3, 'harga' => 35000, 'hari_berlaku' => json_encode([1,2,3,4,5]), 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 3, 'nama_paket' => 'Happy Hour', 'jumlah_jam' => 3, 'harga' => 45000, 'hari_berlaku' => json_encode([1,2,3,4,5]), 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 4, 'nama_paket' => 'Happy Hour', 'jumlah_jam' => 3, 'harga' => 90000, 'hari_berlaku' => json_encode([1,2,3,4,5]), 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 5, 'nama_paket' => 'Happy Hour', 'jumlah_jam' => 3, 'harga' => 100000, 'hari_berlaku' => json_encode([1,2,3,4,5]), 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],

            // Paket Pagi — semua hari, 10:00-12:00, 6 jam
            ['jenis_unit_id' => 1, 'nama_paket' => 'Paket Pagi', 'jumlah_jam' => 6, 'harga' => 40000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 2, 'nama_paket' => 'Paket Pagi', 'jumlah_jam' => 6, 'harga' => 50000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 3, 'nama_paket' => 'Paket Pagi', 'jumlah_jam' => 6, 'harga' => 72000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 4, 'nama_paket' => 'Paket Pagi', 'jumlah_jam' => 6, 'harga' => 150000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 5, 'nama_paket' => 'Paket Pagi', 'jumlah_jam' => 6, 'harga' => 180000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '10:00:00', 'jam_selesai_berlaku' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],

            // Paket Malam — semua hari, 19:00-22:00, 5 jam
            ['jenis_unit_id' => 1, 'nama_paket' => 'Paket Malam', 'jumlah_jam' => 5, 'harga' => 50000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '19:00:00', 'jam_selesai_berlaku' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 2, 'nama_paket' => 'Paket Malam', 'jumlah_jam' => 5, 'harga' => 65000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '19:00:00', 'jam_selesai_berlaku' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 3, 'nama_paket' => 'Paket Malam', 'jumlah_jam' => 5, 'harga' => 90000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '19:00:00', 'jam_selesai_berlaku' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 4, 'nama_paket' => 'Paket Malam', 'jumlah_jam' => 5, 'harga' => 180000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '19:00:00', 'jam_selesai_berlaku' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['jenis_unit_id' => 5, 'nama_paket' => 'Paket Malam', 'jumlah_jam' => 5, 'harga' => 200000, 'hari_berlaku' => null, 'jam_mulai_berlaku' => '19:00:00', 'jam_selesai_berlaku' => '22:00:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}