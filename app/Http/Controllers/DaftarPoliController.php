<?php
namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\Poli;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Ini yang benar


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
                    // dd($no_rm);  // Debug untuk melihat apakah no_rm valid

                } else {
                    return redirect()->back()->withErrors(['message' => 'Pasien tidak ditemukan']);
                }
                $riwayat = DaftarPoli::with(['pasien', 'dokter.poli', 'jadwal', 'periksa', 'periksa.detailPeriksa.obat'])
                ->whereHas('pasien', function ($query) use ($no_rm) {
                    $query->where('no_rm', $no_rm); // Filter berdasarkan no_rm pasien
                })
                ->paginate(10);

                foreach ($riwayat as $item) {
                    // Cek apakah ada data pada relasi periksa
                    if ($item->periksa) {
                        // Jika ada data di tabel periksa, anggap pemeriksaan sudah dilakukan
                        $item->status = 'sudah diperiksa';
                    } else {
                        // Jika tidak ada data di tabel periksa, anggap pemeriksaan belum dilakukan
                        $item->status = 'belum diperiksa';
                    }
                }


                // Ambil data poli untuk dropdown
                $poli = Poli::all();
                $jadwals = JadwalPeriksa::with(['dokter.poli']) // Pastikan relasi dokter memiliki poli
                ->where('status', 'aktif') // Filter hanya jadwal aktif
                ->get();


                // Kirim data pasien dan poli ke view
                return view('pasien.poli', compact('pasien', 'no_rm', 'poli', 'jadwals', 'riwayat'));
    }

    public function create()
    {
        $pasien = Pasien::all();
        return view('pasien.daftar-poli.create', compact('pasien'));
    }

    public function store(Request $request)
{
    $id_pasien = session('pasien.id'); // Pastikan data pasien tersimpan di sesi dengan key 'pasien.id'

    if (!$id_pasien) {
        // Kirim pesan error untuk ditampilkan
        return redirect()->back()->with('error', 'Data pasien tidak ditemukan dalam sesi.');
    }

    // Validasi input
    $validated = $request->validate([
        'id_jadwal' => 'required|exists:jadwal_periksa,id',
        'keluhan' => 'required|string|max:255',
    ]);

    try {
        // Cek apakah jadwal ini valid
        $jadwal = JadwalPeriksa::find($validated['id_jadwal']);
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal yang dipilih tidak valid.');
        }

        // Tentukan awal dan akhir minggu
        $startOfWeek = now()->startOfWeek(); // Awal minggu
        $endOfWeek = now()->endOfWeek();     // Akhir minggu

        // Hitung nomor antrian berdasarkan jadwal di tabel daftar_poli
        $noAntrian = DaftarPoli::where('id_jadwal', $validated['id_jadwal'])
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek]) // Filter minggu ini
            ->count() + 1;

        // Simpan data ke tabel
        DaftarPoli::create([
            'id_pasien' => $id_pasien,
            'id_jadwal' => $validated['id_jadwal'],
            'keluhan' => $validated['keluhan'],
            'no_antrian' => $noAntrian,
        ]);

        return redirect()->route('pasien.poli')->with('success', 'Pendaftaran berhasil.');
    } catch (\Exception $e) {
        // Log error di server dan kirim pesan error ke klien
        \Log::error('Pendaftaran Poli Gagal: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }
}

}
