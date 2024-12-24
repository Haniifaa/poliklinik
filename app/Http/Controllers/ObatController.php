<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $obat = Obat::all();

        $search = $request->input('search');

        $obat = Obat::search($search)->paginate(10);
        // dd($obat); // Periksa tipe data

        return view('admin.masterdata.obat', compact('obat', 'search'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
{
    // Validasi data input
    $request->validate([
        'nama_obat' => 'required|string|max:50',
        'kemasan' => 'required|string|max:35',
        'harga' => 'required|integer',
    ], [
        'nama_obat.required' => 'Nama obat harus diisi.',
        'kemasan.required' => 'Kemasan obat harus diisi.',
        'harga.required' => 'Harga obat harus diisi.',
    ]);

    try {
        // Simpan data obat
        Obat::create($request->all());
        return redirect()->route('admin.masterdata.obat')
                         ->with('success', 'Obat berhasil ditambahkan.');
    } catch (\Exception $e) {
        return redirect()->route('admin.masterdata.obat')
                         ->withErrors('Gagal menambahkan obat: ' . $e->getMessage());
    }
}

public function edit($id)
{
    $obat = Obat::findOrFail($id);
    return response()->json($obat);

}

public function update(Request $request, $id)
{
    // Mencari pasien berdasarkan ID
    $obat = Obat::find($id);

    // Jika pasien tidak ditemukan, kembalikan response JSON dengan error 404
    if (!$obat) {
        return response()->json(['error' => 'Obat tidak ditemukan'], 404);
    }

    // Validasi data input
    $validated = $request->validate([
        'nama_obat' => 'required|string|max:50',
        'kemasan' => 'required|string|max:35',
        'harga' => 'required|integer',
    ], [
        'nama_obat.required' => 'Nama obat harus diisi.',
        'kemasan.required' => 'Kemasan obat harus diisi.',
        'harga.required' => 'Harga obat harus diisi.',
    ]);

    // Update data pasien setelah validasi berhasil
    $obat->update($validated);

    // Kembalikan response ke halaman sebelumnya dengan pesan sukses
    return redirect()->route('admin.masterdata.obat')->with('success', 'Obat berhasil diperbarui.');
}



    public function destroy($id)
    {
        try {
            // Cari dan hapus data pasien
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return redirect()->route('admin.masterdata.obat')
                             ->with('success', 'Data obat berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.masterdata.obat')
                             ->withErrors('Gagal menghapus data obat: ' . $e->getMessage());
        }
    }
}
