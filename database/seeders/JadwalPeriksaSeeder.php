<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini


class JadwalPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal_periksa')->insert([
            [
                'id_dokter' => 1,
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '12:00:00',
                'status' => 'Aktif',

            ],
            [
                'id_dokter' => 2,
                'hari' => 'Selasa',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '12:00:00',
                'status' => 'Tidak Aktif',
            ],
        ]);
    }
}
