<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('poli')->insert([
            ['nama_poli' => 'Poli Umum', 'keterangan' => 'Layanan pemeriksaan umum'],
            ['nama_poli' => 'Poli Anak', 'keterangan' => 'Layanan pemeriksaan anak'],
        ]);
    }
}
