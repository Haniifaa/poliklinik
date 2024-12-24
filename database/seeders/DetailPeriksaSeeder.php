<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini


class DetailPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_periksa')->insert([
            ['id_periksa' => 1, 'id_obat' => 1],
            ['id_periksa' => 1, 'id_obat' => 2],
        ]);
    }
}
