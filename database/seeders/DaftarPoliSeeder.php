<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class DaftarPoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('daftar_poli')->insert([
            [
                'id_pasien' => 1,
                'id_jadwal' => 1,
                'keluhan' => 'Demam tinggi',
                'no_antrian' => 1,
            ],
            [
                'id_pasien' => 2,
                'id_jadwal' => 2,
                'keluhan' => 'Batuk dan pilek',
                'no_antrian' => 2,
            ],
        ]);
    }
}
