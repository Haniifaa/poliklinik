<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini


class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pasien')->insert([
            [
                'nama' => 'John Doe',
                'alamat' => 'Jl. Mawar No. 123',
                'no_ktp' => '1234567890123456',
                'no_hp' => '081234567890',
                'no_rm' => 'RM001',
            ],
            [
                'nama' => 'Jane Doe',
                'alamat' => 'Jl. Melati No. 456',
                'no_ktp' => '9876543210987654',
                'no_hp' => '081987654321',
                'no_rm' => 'RM002',
            ],
        ]);
    }
}
