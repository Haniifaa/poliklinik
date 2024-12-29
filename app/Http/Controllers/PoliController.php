<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\DaftarPoli;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index(Request $request)
    {
        $poli = Poli::all();

        $search = $request->input('search');

        $poli = Poli::search($search)->paginate(10);

        return view('admin.masterdata.poli', compact('poli', 'search'));
    }

//     public function showPoliPasien()
//     {
// // Mengambil data pasien dari sesi
// $pasien = session('pasien');

// // Mengambil nama pasien dari data sesi
// if ($pasien) {
//     $namaPasien = $pasien->nama; // Ambil nama dari model Pasien yang ada di sesi
// } else {
//     // Jika tidak ada data pasien di sesi
//     return redirect()->route('pasien.login')->withErrors(['message' => 'Harap login terlebih dahulu']);
// }

//         // Cek jika pasien ditemukan
//         if ($pasien) {
//             $no_rm = $pasien->no_rm; // Ambil no rekam medis pasien
//         } else {
//             return redirect()->back()->withErrors(['message' => 'Pasien tidak ditemukan']);
//         }

//         // Ambil data poli untuk dropdown
//         $poli = Poli::all();

//         // Kirim data pasien dan poli ke view
//         return view('pasien.poli', compact('pasien', 'no_rm', 'poli'));
//     }

    public function create()
    {
        return view('poli.create');
    }

    public function store(Request $request)
{
    // Validasi data input
    $request->validate([
        'nama_poli' => 'required|string|max:25',
        'keterangan' => 'nullable|string',
    ], [
        'nama.required' => 'Nama poli harus diisi.',
        'keterangan.required' => 'Keterangan poli harus diisi.',
    ]);

    try {
        // Simpan data poli
        Poli::create($request->all());
        return redirect()->route('admin.masterdata.poli')
                         ->with('success', 'Poli berhasil ditambahkan.');
    } catch (\Exception $e) {
        return redirect()->route('admin.masterdata.poli')
                         ->withErrors('Gagal menambahkan poli: ' . $e->getMessage());
    }
}

public function edit($id)
{
    $poli = Poli::findOrFail($id);
    return response()->json($poli);

}

public function update(Request $request, $id)
{
    // Mencari pasien berdasarkan ID
    $poli = Poli::find($id);

    // Jika pasien tidak ditemukan, kembalikan response JSON dengan error 404
    if (!$poli) {
        return response()->json(['error' => 'Poli tidak ditemukan'], 404);
    }

    // Validasi data input
    $validated = $request->validate([
        'nama_poli' => 'required|string|max:25',
        'keterangan' => 'nullable|string',
    ], [
        'nama.required' => 'Nama poli harus diisi.',
        'keterangan.required' => 'Keterangan poli harus diisi.',

    ]);

    // Update data pasien setelah validasi berhasil
    $poli->update($validated);

    // Kembalikan response ke halaman sebelumnya dengan pesan sukses
    return redirect()->route('admin.masterdata.poli')->with('success', 'Poli berhasil diperbarui.');
}



    public function destroy($id)
    {
        try {
            // Cari dan hapus data pasien
            $poli = Poli::findOrFail($id);
            $poli->delete();
            return redirect()->route('admin.masterdata.poli')
                             ->with('success', 'Data poli berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.masterdata.poli')
                             ->withErrors('Gagal menghapus data poli: ' . $e->getMessage());
        }
    }

    // Menampilkan daftar jadwal periksa
    // public function pasienpoli()
    // {
    //     $riwayat = DaftarPoli::with(['pasien', 'dokter', 'poli', 'jadwal', 'periksa'])->paginate(10);

    //     return view('pasien.poli', compact('riwayat'));
    // }
}
