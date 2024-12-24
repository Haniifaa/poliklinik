<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periksa;
use App\Models\DaftarPoli;
use App\Models\Pasien;
use App\Models\DetailPeriksa;
use App\Models\JadwalPeriksa;
use App\Models\Dokter;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index(Request $request)
    {
        $dokterId = session('dokter')->id;
        if (!$dokterId) {
            return redirect()->route('login.dokter')->with('error', 'Session dokter tidak ditemukan. Silakan login kembali.');
        }

        $search = $request->input('search');

        $periksa = DaftarPoli::with(['pasien', 'dokter', 'poli', 'jadwal', 'periksa'])
        ->leftJoin('periksa', 'daftar_poli.id', '=', 'periksa.id_daftar_poli') // Gabungkan dengan tabel periksa
        ->join('jadwal_periksa', 'daftar_poli.id_jadwal', '=', 'jadwal_periksa.id') // Gabungkan dengan tabel jadwal
        ->select('daftar_poli.*', 'periksa.id as id_periksa') // Ambil id_periksa dari tabel periksa

        ->whereHas('jadwal', function ($query) use ($dokterId) {
            $query->where('id_dokter', $dokterId); // Filter berdasarkan ID dokter di jadwal_periksa
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%'); // Filter berdasarkan nama pasien
            });
        })

        ->orderByRaw("FIELD(jadwal_periksa.hari, 'Sabtu','Jumat','Kamis','Rabu', 'Selasa', 'Senin')") // Urutkan hari secara manual
        ->orderBy('no_antrian', 'asc') // Urutkan nomor urut dalam hari yang sama
            ->paginate(10);



        return view('dokter.periksa-pasien', compact('periksa', 'search'));
    }

    public function periksa($id, Request $request)
    {
        $pasien = DaftarPoli::with('pasien')->find($id); // Ambil pasien dari daftar poli
        $daftarPoli = DaftarPoli::with('pasien')->get(); // Ambil semua daftar poli
        $obatList = Obat::all(); // Ambil semua data obat
        $tgl_periksa = now()->format('Y-m-d\TH:i'); // Format untuk input datetime-local


        return view('dokter.periksa', compact('pasien','daftarPoli', 'obatList', 'tgl_periksa', 'tgl_periksa')); // Pastikan kamu memiliki view 'dokter_dashboard'
    }

    public function store(Request $request)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',  // Pastikan id_daftar_poli valid
            'tgl_periksa'    => 'required|date|after_or_equal:today', // Validasi untuk tanggal
            'catatan'        => 'nullable|string',
            'id_obat'        => 'required|exists:obat,id',  // Validasi bahwa obat ada di database
        ]);

        // Log data validasi untuk memastikan data benar
        \Log::info('Data validasi berhasil:', $validated);

        // Mulai transaksi
        DB::beginTransaction();

        // Cari obat
        $obat = Obat::find($validated['id_obat']);
        if (!$obat) {
            throw new \Exception("Obat dengan ID {$validated['id_obat']} tidak ditemukan.");
        }

        $biayaObat = $obat->harga;
        \Log::info('Harga Obat:', ['harga' => $biayaObat]);

        // Biaya dokter tetap
        $biayaDokter = 150000;

        // Hitung total biaya periksa
        $biayaPeriksa = $biayaObat + $biayaDokter;

        // Simpan data ke tabel 'periksa'
        $periksa = Periksa::create([
            'id_daftar_poli' => $validated['id_daftar_poli'],
            'tgl_periksa'    => $validated['tgl_periksa'],
            'catatan'        => $validated['catatan'],
            'biaya_periksa'  => $biayaPeriksa,
        ]);

        if (!$periksa) {
            throw new \Exception('Gagal menyimpan data ke tabel "periksa".');
        }

        \Log::info('Data Periksa Tersimpan:', $periksa->toArray());

        // Simpan data ke tabel 'detail_periksa'
        $detailPeriksa = DetailPeriksa::create([
            'id_periksa' => $periksa->id,
            'id_obat'    => $validated['id_obat'],
        ]);

        if (!$detailPeriksa) {
            throw new \Exception('Gagal menyimpan data ke tabel "detail_periksa".');
        }

        \Log::info('Data Detail Periksa Tersimpan:', $detailPeriksa->toArray());

        // Commit transaksi
        DB::commit();

        return redirect()->route('dokter.periksa-pasien')->with('success', 'Data berhasil disimpan!');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi error
        DB::rollBack();

        // Log error dengan detailnya
        \Log::error('Terjadi kesalahan:', [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ]);

        // Redirect kembali dengan pesan error
        return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
    }
}



}
