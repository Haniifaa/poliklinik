<?php
namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\Poli;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;

class DaftarPoliController extends Controller
{
    public function index(Request $request)
    {
        $daftarPoli = DaftarPoli::with(['pasien', 'jadwal.dokter.poli'])->get();
        return view('daftar_poli.index', compact('daftarPoli'));
    }

    public function showPoliPasien(Request $request)
    {
        // Mengambil data pasien dari sesi
        $pasien = session('pasien');

        // Mengambil nama pasien dari data sesi
        if ($pasien) {
            $namaPasien = $pasien->nama; // Ambil nama dari model Pasien yang ada di sesi
        } else {
            // Jika tidak ada data pasien di sesi
            return redirect()->route('pasien.login')->withErrors(['message' => 'Harap login terlebih dahulu']);
        }

                // Cek jika pasien ditemukan
                if ($pasien) {
                    $no_rm = $pasien->no_rm; // Ambil no rekam medis pasien
                } else {
                    return redirect()->back()->withErrors(['message' => 'Pasien tidak ditemukan']);
                }

                // Ambil data poli untuk dropdown
                $poli = Poli::all();
                $jadwals = JadwalPeriksa::with(['dokter.poli']) // Pastikan relasi dokter memiliki poli
                ->where('status', 'aktif') // Filter hanya jadwal aktif
                ->get();


                // Kirim data pasien dan poli ke view
                return view('pasien.poli', compact('pasien', 'no_rm', 'poli', 'jadwals'));
    }

    public function create()
    {
        $pasien = Pasien::all();
        return view('pasien.daftar-poli.create', compact('pasien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);


        // Cek apakah jadwal ini sudah ada di tabel daftar_poli
        $jadwal = DaftarPoli::where('id_jadwal', $request->id_jadwal)
        ->first();

    if (!$jadwal) {
        return redirect()->back()->with('error', 'Jadwal yang dipilih tidak valid.');
    }

    // Tentukan awal dan akhir minggu
    $startOfWeek = now()->startOfWeek(); // Awal minggu ini
    $endOfWeek = now()->endOfWeek();     // Akhir minggu ini

    // Hitung nomor antrian berdasarkan jadwal di tabel daftar_poli
    $noAntrian = DaftarPoli::where('id_jadwal', $request->id_jadwal)
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]) // Filter minggu ini
        ->count() + 1; // Tambah 1 untuk pasien baru

        DaftarPoli::create([
            'id_pasien' => session('pasien')->id, // Ambil dari session
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $noAntrian,
        ]);

        return redirect()->route('pasien.poli')->with('success', 'Pendaftaran berhasil');

    }
}
