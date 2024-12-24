<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class PeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periksa')->insert([
            [
                'id_daftar_poli' => 1,
                'tgl_periksa' => now(),
                'catatan' => 'Pasien membutuhkan antibiotik.',
                'biaya_periksa' => 150000,
            ],
        ]);
    }
}
