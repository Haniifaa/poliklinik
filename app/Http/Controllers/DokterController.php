<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $dokter = Dokter::all();
        $poli = Poli::all();

        // $dokter = Dokter::with('poli')->paginate(10);


        $search = $request->input('search');

        $dokter = Dokter::search($search)->paginate(10);

        return view('admin.masterdata.dokter', compact('dokter', 'search', 'poli'));

    }

    public function dashboard()
    {
        return view('dokter.dashboard');
    }

    public function create()
    {
        return view('dokter.create');
    }

    public function store(Request $request)
{
    // Validasi data input
    $request->validate([
        'nama' => 'required|max:255',
        'alamat' => 'required|max:255',
        'no_hp' => 'required|max:50',
        'id_poli' => 'required|exists:poli,id',
    ], [
        'nama.required' => 'Nama dokter harus diisi.',
        'alamat.required' => 'Alamat dokter harus diisi.',
        'no_hp.required' => 'Nomor HP harus diisi.',
        'id_poli.required' => 'Poli harus dipilih.',
    ]);

    try {
        Dokter::create($request->all());
        return redirect()->route('admin.masterdata.dokter')->with('success', 'Dokter berhasil ditambahkan.');
    } catch (\Exception $e) {
        return redirect()->route('admin.masterdata.dokter')->withErrors('Gagal menambahkan dokter: ' . $e->getMessage());
    }
}

public function edit($id)
{
    $dokter = Dokter::findOrFail($id);
    return response()->json($dokter);

}

public function update(Request $request, $id)
{
    // Mencari pasien berdasarkan ID
    $dokter = Dokter::find($id);

    // Jika pasien tidak ditemukan, kembalikan response JSON dengan error 404
    if (!$dokter) {
        return response()->json(['error' => 'Dokter tidak ditemukan'], 404);
    }

    // Validasi data input
    $validated = $request->validate([
        'nama' => 'required|max:255',
        'alamat' => 'required|max:255',
        'no_hp' => 'required|max:50',
        'id_poli' => 'required|exists:poli,id',
    ], [
        'nama.required' => 'Nama dokter harus diisi.',
        'alamat.required' => 'Alamat dokter harus diisi.',
        'no_hp.required' => 'Nomor HP harus diisi.',
        'id_poli.required' => 'Poli harus dipilih.',
    ]);

    // Update data pasien setelah validasi berhasil
    $dokter->update($validated);

    // Kembalikan response ke halaman sebelumnya dengan pesan sukses
    return redirect()->route('admin.masterdata.dokter')->with('success', 'Dokter berhasil diperbarui.');
}



    public function destroy($id)
    {
        try {
            // Cari dan hapus data dokter
            $dokter = Dokter::findOrFail($id);
            $dokter->delete();
            return redirect()->route('admin.masterdata.dokter')
                             ->with('success', 'Data dokter berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.masterdata.dokter')
                             ->withErrors('Gagal menghapus data dokter: ' . $e->getMessage());
        }
    }

    public function profile()
{
    $dokter = session('dokter'); // Ambil data dokter dari sesi
    $dokter = Dokter::with('poli')->findOrFail($dokter->id); // Cari dokter berdasarkan id dari sesi
    $poli = Poli::all(); // Semua data poli
    return view('dokter.profile', compact('dokter', 'poli'));

}


    public function profileEdit()
    {
        $dokter = session('dokter');

        // $dokterId = session('dokter')->id;

        // $dokter = Dokter::findOrFail($id);
        $poli = Poli::all(); // Mengambil semua data poli
        return view('dokter.edit', compact('dokter', 'poli'));
    }

    public function profileUpdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
        ]);

        $dokter = Dokter::findOrFail($id);
        $dokter->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
        ]);

        return redirect()->route('dokter.profile', $dokter->id)->with('success', 'Profil dokter berhasil diperbarui.');
    }


}

