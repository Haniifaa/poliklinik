<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatPasien;

class RiwayatPasienController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index()
    {
        return view('dokter.riwayat-pasien');
    }
}
