<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini


class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dokter')->insert([
            [
                'nama' => 'Andi',
                'alamat' => 'Semanggi',
                'no_hp' => '0812233445566',
                'id_poli' => 1,
            ],
            [
                'nama' => 'Dr. Budi',
                'alamat' => 'Jl. Sehat No. 15',
                'no_hp' => '081234567899',
                'id_poli' => 2,
            ],
        ]);
    }
}
