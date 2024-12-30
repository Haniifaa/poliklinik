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
use Illuminate\Support\Facades\Log;


class PeriksaPasienController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index(Request $request)
    {
        $dokter = session('dokter');

        $dokterId = session('dokter')->id;
        if (!$dokterId) {
            return redirect()->route('login.dokter')->with('error', 'Session dokter tidak ditemukan. Silakan login kembali.');
        }
        $obatList = Obat::all(); // Ambil semua data obat

        $search = $request->input('search');

        $periksa = DaftarPoli::with(['pasien', 'dokter', 'poli', 'jadwal', 'periksa'])
        ->leftJoin('periksa', 'daftar_poli.id', '=', 'periksa.id_daftar_poli') // Gabungkan dengan tabel periksa
    ->join('jadwal_periksa', 'daftar_poli.id_jadwal', '=', 'jadwal_periksa.id')
    ->whereHas('jadwal', function ($query) use ($dokterId) {
        $query->where('id_dokter', $dokterId);
    })
    ->when($search, function ($query, $search) {
        $query->whereHas('pasien', function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%');
        });
    })
    ->select('daftar_poli.*', 'periksa.id as id_periksa', 'jadwal_periksa.hari') // Pastikan kolom yang diperlukan disertakan
    ->orderByRaw("FIELD(jadwal_periksa.hari, 'Sabtu','Jumat','Kamis','Rabu', 'Selasa', 'Senin')")
    ->orderBy('no_antrian', 'asc')
    ->paginate(10);

        return view('dokter.periksa-pasien', compact('periksa', 'search', 'obatList'));
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
            'id_daftar_poli' => 'required|exists:daftar_poli,id', // Validasi bahwa ID daftar poli ada
            'tgl_periksa'    => 'required|date|after_or_equal:today', // Tanggal periksa harus valid
            'catatan'        => 'nullable|string',
            'id_obat'        => 'required|array', // Pastikan id_obat adalah array
            'id_obat.*'      => 'exists:obat,id', // Validasi bahwa setiap obat ada di database
        ]);

        \Log::info('Data validasi berhasil:', $validated);

        // Mulai transaksi
        DB::beginTransaction();

        // Biaya dokter tetap
        $biayaDokter = 150000;

        // Inisialisasi total biaya obat
        $totalBiayaObat = 0;

        // Proses setiap obat
        foreach ($validated['id_obat'] as $idObat) {
            $obat = Obat::find($idObat);

            if (!$obat) {
                throw new \Exception("Obat dengan ID {$idObat} tidak ditemukan.");
            }

            $totalBiayaObat += $obat->harga; // Tambahkan harga obat ke total biaya
        }

        // Hitung total biaya pemeriksaan
        $biayaPeriksa = $biayaDokter + $totalBiayaObat;

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

        // Simpan data ke tabel 'detail_periksa' untuk setiap obat
        foreach ($validated['id_obat'] as $idObat) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $idObat,
            ]);
        }

        \Log::info('Data Detail Periksa Tersimpan untuk ID Obat:', $validated['id_obat']);

        // Commit transaksi
        DB::commit();

        return redirect()->route('dokter.periksa-pasien')->with('success', 'Data berhasil disimpan!');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi error
        DB::rollBack();

        \Log::error('Terjadi kesalahan:', [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ]);

        return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
    }
}


public function edit($id)
{
    $periksa = Periksa::with('obat', 'daftarPoli.pasien')->find($id);

    if (!$periksa) {
        \Log::warning('Periksa tidak ditemukan untuk ID: ' . $id);
        return response()->json(['success' => false, 'message' => 'Periksa tidak ditemukan'], 404);
    }

    $daftarObat = Obat::select('id', 'nama_obat', 'kemasan', 'harga')->get();


    return response()->json([
        'success' => true,
        'data' => [
            'id' => $periksa->id,
            'tgl_periksa' => $periksa->tgl_periksa,
            'catatan' => $periksa->catatan,
            'biaya_periksa' => $periksa->biaya_periksa,
            'id_daftar_poli' => $periksa->id_daftar_poli,
            'nama' => $periksa->daftarPoli->pasien->nama,
            'obat' => $periksa->obat->map(function ($obat) {
                return [
                    'id' => $obat->id, // Tambahkan ID obat untuk dropdown
                    'nama_obat' => $obat->nama_obat,
                    'kemasan' => $obat->kemasan,
                    'harga' => $obat->harga,
                ];
            }),
            'daftar_obat' => $daftarObat, // Tambahkan daftar obat ke respons
        ]
    ]);
}








public function update(Request $request, $id)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'tgl_periksa'   => 'required|date',
            'catatan'       => 'nullable|string|max:255',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat'          => 'nullable|array',
            'obat.*.id'     => 'exists:obat,id',
        ]);

        // Cari data periksa berdasarkan ID
        $periksa = Periksa::findOrFail($id);

        // Mulai transaksi
        DB::beginTransaction();

        // Update data periksa
        $periksa->update([
            'tgl_periksa'   => $validated['tgl_periksa'],
            'catatan'       => $validated['catatan'],
            'biaya_periksa' => $validated['biaya_periksa'],
        ]);

        // Update detail obat jika ada
        if (!empty($validated['obat'])) {
            // Sinkronisasi detail obat
            $detailData = collect($validated['obat'])->map(function ($obat) use ($periksa) {
                return [
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat['id'],
                ];
            });

            DetailPeriksa::where('id_periksa', $periksa->id)->delete();
            DetailPeriksa::insert($detailData->toArray());
        }

        // Commit transaksi
        DB::commit();

        return redirect()->route('dokter.periksa-pasien')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    } catch (ModelNotFoundException $e) {
        DB::rollBack();

        return redirect()->back()->withErrors(['error' => 'Data pemeriksaan tidak ditemukan.']);
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();

        \Log::error('Error saat update data pemeriksaan:', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()]);
    }
}


}
