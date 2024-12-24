<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PasienController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index(Request $request)
    {
        $pasien = Pasien::all();

        $search = $request->input('search');

        $pasien = Pasien::search($search)->paginate(10);

        return view('admin.masterdata.pasien', compact('pasien', 'search'));
    }



    public function dashboard()
    {
        $pasien = session('pasien');

        if (!$pasien) {
            return redirect()->route('login.pasien')->with('error', 'Silakan daftar terlebih dahulu.');
        }


        return view('pasien.dashboard');
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
{
    // Validasi data input
    $request->validate([
        'nama' => 'required|max:255',
        'alamat' => 'required|max:255',
        'no_ktp' => 'required|max:255|unique:pasien,no_ktp',
        'no_hp' => 'required|max:50',
    ], [
        'nama.required' => 'Nama pasien harus diisi.',
        'alamat.required' => 'Alamat pasien harus diisi.',
        'no_ktp.required' => 'Nomor KTP harus diisi.',
        'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
        'no_hp.required' => 'Nomor HP harus diisi.',
    ]);

    try {
        // Generate nomor RM
        $currentYearMonth = now()->format('Ym'); // TahunBulan saat ini
        $totalPasien = Pasien::whereYear('created_at', now()->year)
                            ->whereMonth('created_at', now()->month)
                            ->count(); // Hitung jumlah pasien yang terdaftar di bulan dan tahun ini

        $noRm = $currentYearMonth . '-' . str_pad($totalPasien + 1, 3, '0', STR_PAD_LEFT); // Format no_rm (misalnya 202412-001)

        // Simpan data pasien dengan nomor RM yang baru
        $pasienData = $request->all();
        $pasienData['no_rm'] = $noRm; // Menambahkan no_rm yang sudah digenerate

        // Simpan pasien ke database
        Pasien::create($pasienData);

        return redirect()->route('admin.masterdata.pasien')
                         ->with('success', 'Pasien berhasil ditambahkan.');
    } catch (\Exception $e) {
        return redirect()->route('admin.masterdata.pasien')
                         ->withErrors('Gagal menambahkan pasien: ' . $e->getMessage());
    }
}

public function edit($id)
{
    $pasien = Pasien::findOrFail($id);
    return response()->json($pasien);

}

public function update(Request $request, $id)
{
    // Mencari pasien berdasarkan ID
    $pasien = Pasien::find($id);

    // Jika pasien tidak ditemukan, kembalikan response JSON dengan error 404
    if (!$pasien) {
        return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
    }

    // Mengambil nilai no_rm yang lama dan menyimpannya untuk update
    $oldNoRm = $pasien->no_rm;

    // Validasi data input
    $validated = $request->validate([
        'nama' => 'required|max:255',
        'alamat' => 'required|max:255',
        'no_ktp' => 'required|max:255|unique:pasien,no_ktp,' . $pasien->id,
        'no_hp' => 'required|max:50',
        // Jangan validasi no_rm karena sudah pasti tidak berubah
        'no_rm' => 'required|max:25|unique:pasien,no_rm,' . $pasien->id,
    ], [
        'nama.required' => 'Nama pasien harus diisi.',
        'alamat.required' => 'Alamat pasien harus diisi.',
        'no_ktp.required' => 'Nomor KTP harus diisi.',
        'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
        'no_hp.required' => 'Nomor HP harus diisi.',
        'no_rm.required' => 'Nomor RM harus diisi.',
        'no_rm.unique' => 'Nomor RM sudah terdaftar.',
    ]);

    // Update data pasien setelah validasi berhasil
    // Pastikan no_rm tidak diubah, tetap gunakan nilai yang lama
    $validated['no_rm'] = $oldNoRm;

    $pasien->update($validated);

    // Kembalikan response ke halaman sebelumnya dengan pesan sukses
    return redirect()->route('admin.masterdata.pasien')->with('success', 'Pasien berhasil diperbarui.');
}



    public function destroy($id)
    {
        try {
            // Cari dan hapus data pasien
            $pasien = Pasien::findOrFail($id);
            $pasien->delete();
            return redirect()->route('admin.masterdata.pasien')
                             ->with('success', 'Data pasien berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.masterdata.pasien')
                             ->withErrors('Gagal menghapus data pasien: ' . $e->getMessage());
        }
    }



    public function register(Request $request)
    {
        // Validasi input
    // Validasi input
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|unique:pasien,no_ktp',
        'no_hp' => 'required|string|max:15',
    ], [
        'nama.required' => 'Nama pasien harus diisi.',
        'alamat.required' => 'Alamat pasien harus diisi.',
        'no_ktp.required' => 'Nomor KTP harus diisi.',
        'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
        'no_hp.required' => 'Nomor HP harus diisi.',
    ]);

    // Jika validasi gagal, kembali dengan pesan error
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        // Generate nomor RM
        $currentYearMonth = now()->format('Ym'); // TahunBulan saat ini
        $totalPasien = Pasien::whereYear('created_at', now()->year)
                            ->whereMonth('created_at', now()->month)
                            ->count(); // Hitung jumlah pasien yang terdaftar di bulan dan tahun ini

        // Format no_rm (misalnya 202412-001), pastikan tidak ada duplikasi
        $noRm = $currentYearMonth . '-' . str_pad($totalPasien + 1, 3, '0', STR_PAD_LEFT);

        // Cek apakah no_rm sudah ada
        while (Pasien::where('no_rm', $noRm)->exists()) {
            $totalPasien++;
            $noRm = $currentYearMonth . '-' . str_pad($totalPasien + 1, 3, '0', STR_PAD_LEFT);
        }

        // Simpan data pasien dengan nomor RM yang baru
        $pasienData = $request->all();
        $pasienData['no_rm'] = $noRm; // Menambahkan no_rm yang sudah digenerate

        // Simpan pasien ke database
        $pasien = Pasien::create($pasienData);

        // Arahkan kembali ke halaman pendaftaran dengan pesan sukses
        return redirect()->route('login.pasien')->with('success', 'Pendaftaran berhasil. Silakan lanjutkan untuk login.');

    } catch (\Exception $e) {
        // Jika terjadi error, redirect dengan pesan error
        return back()->withErrors(['message' => 'Gagal melakukan pendaftaran: ' . $e->getMessage()]);
    }
        // $validator = Validator::make($request->all(), [
        //     'nama' => 'required|string|max:255',
        //     'alamat' => 'required|string',
        //     'no_ktp' => 'required|string|unique:pasien,no_ktp',
        //     'no_hp' => 'required|string|max:15',
        // ], [
        //     'nama.required' => 'Nama pasien harus diisi.',
        //     'alamat.required' => 'Alamat pasien harus diisi.',
        //     'no_ktp.required' => 'Nomor KTP harus diisi.',
        //     'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
        //     'no_hp.required' => 'Nomor HP harus diisi.',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // try {
        //     // Generate nomor RM
        //     $currentYearMonth = now()->format('Ym'); // TahunBulan saat ini
        //     $totalPasien = Pasien::whereYear('created_at', now()->year)
        //                         ->whereMonth('created_at', now()->month)
        //                         ->count(); // Hitung jumlah pasien yang terdaftar di bulan dan tahun ini

        //     $noRm = $currentYearMonth . '-' . str_pad($totalPasien + 1, 3, '0', STR_PAD_LEFT); // Format no_rm (misalnya 202412-001)

        //     // Simpan data pasien dengan nomor RM yang baru
        //     $pasienData = $request->all();
        //     $pasienData['no_rm'] = $noRm; // Menambahkan no_rm yang sudah digenerate

        //     // Simpan pasien ke database
        //     $pasien = Pasien::create($pasienData);

        //     // Simpan data pasien di sesi
        //     session(['pasien' => $pasien]);

        //     // Arahkan ke dashboard pasien
        //     return redirect()->route('pasien.dashboard')->with('success', 'Pendaftaran berhasil. Selamat datang!');
        // } catch (\Exception $e) {
        //     // Jika terjadi error, redirect dengan pesan error
        //     return back()->withErrors(['message' => 'Gagal melakukan pendaftaran: ' . $e->getMessage()]);
        // }
    }


    public function checkExisting(Request $request)
    {
        $request->validate([
            'no_ktp' => 'required|string',
        ]);

        $pasien = Pasien::where('no_ktp', $request->no_ktp)->first();

        if ($pasien) {
            return response()->json([
                'message' => 'Pasien already exists.',
                'data' => $pasien,
            ]);
        }

        return response()->json(['message' => 'Pasien not found.'], 404);
    }
}
