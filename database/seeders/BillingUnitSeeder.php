<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BillingUnit;
use App\Models\JenisUnitBooking;

class BillingUnitSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil jenis unit berdasarkan tipe
        $ps4      = JenisUnitBooking::where('tipe', 'PS4')->first();
        $ps4pro   = JenisUnitBooking::where('tipe', 'PS4 Pro')->first();
        $ps5      = JenisUnitBooking::where('tipe', 'PS5')->first();
        $vipPs5   = JenisUnitBooking::where('tipe', 'PS5 VIP')->first();
        $vvipPs5  = JenisUnitBooking::where('tipe', 'PS5 VVIP')->first();

        $units = [
            // Lantai 1 - PS4 Unit 1-8
            ['lantai' => 1, 'nama_unit' => 'Unit 1',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 2',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 3',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 4',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 5',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 6',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 7',  'jenis_unit_id' => $ps4->id],
            ['lantai' => 1, 'nama_unit' => 'Unit 8',  'jenis_unit_id' => $ps4->id],

            // Lantai 1 - PS5
            ['lantai' => 1, 'nama_unit' => 'Unit 9',  'jenis_unit_id' => $ps5->id],

            // Lantai 1 - VIP PS5
            ['lantai' => 1, 'nama_unit' => 'VIP',     'jenis_unit_id' => $vipPs5->id],

            // Lantai 2 - PS4 Unit 11-16
            ['lantai' => 2, 'nama_unit' => 'Unit 11', 'jenis_unit_id' => $ps4->id],
            ['lantai' => 2, 'nama_unit' => 'Unit 12', 'jenis_unit_id' => $ps4->id],
            ['lantai' => 2, 'nama_unit' => 'Unit 13', 'jenis_unit_id' => $ps4->id],
            ['lantai' => 2, 'nama_unit' => 'Unit 14', 'jenis_unit_id' => $ps4->id],
            ['lantai' => 2, 'nama_unit' => 'Unit 15', 'jenis_unit_id' => $ps4->id],
            ['lantai' => 2, 'nama_unit' => 'Unit 16', 'jenis_unit_id' => $ps4->id],

            // Lantai 2 - PS4 Pro
            ['lantai' => 2, 'nama_unit' => 'Unit 17', 'jenis_unit_id' => $ps4pro->id],

            // Lantai 2 - PS5
            ['lantai' => 2, 'nama_unit' => 'Unit 18', 'jenis_unit_id' => $ps5->id],

            // Lantai 2 - VVIP PS5
            ['lantai' => 2, 'nama_unit' => 'VVIP 1',  'jenis_unit_id' => $vvipPs5->id],
            ['lantai' => 2, 'nama_unit' => 'VVIP 2',  'jenis_unit_id' => $vvipPs5->id],
        ];

        foreach ($units as $unit) {
            BillingUnit::create($unit);
        }
    }
}