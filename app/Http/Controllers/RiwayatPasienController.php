<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatPasien;
use App\Models\Pasien;
use App\Models\Periksa;
use App\Models\JadwalPeriksa;




class RiwayatPasienController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index()
    {
        $dokter = session('dokter'); // Ambil data dokter dari session
        $riwayat = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwal.dokter',
            'daftarPoli.dokter', 'detailPeriksa.obat',
            'detailPeriksa:id_periksa,id_obat,created_at,updated_at' // Pastikan hanya kolom yang ada di detail_periksa yang di-load
        ]) // Eager load jadwal dan dokter
        ->whereHas('daftarPoli.jadwal.dokter', function ($query) use ($dokter) {
            $query->where('id_dokter', $dokter->id); // Menyaring berdasarkan id_dokter di tabel jadwal
        })
        ->get();

        return view('dokter.riwayat-pasien', compact('riwayat'));
    }}
