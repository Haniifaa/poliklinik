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

           // Ambil data Periksa dengan relasi yang dibutuhkan menggunakan get()
    $periksa = Periksa::with('obat', 'daftarPoli.pasien')->where('id', $id)->get();

    if ($periksa->isEmpty()) {
        \Log::warning('Periksa tidak ditemukan untuk ID: ' . $id);
        return response()->json(['error' => 'Periksa tidak ditemukan'], 404);
    }

    // Log data periksa untuk debugging
    \Log::info('Periksa ditemukan:', $periksa->toArray());
    dd($periksa);

    return response()->json($periksa);


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



//     public function store(Request $request)
// {
//     try {
//         // Validasi input
//         $validated = $request->validate([
//             'id_daftar_poli' => 'required|exists:daftar_poli,id',  // Validasi bahwa ID daftar poli ada
//             'tgl_periksa'    => 'required|date|after_or_equal:today', // Tanggal periksa harus valid
//             'catatan'        => 'nullable|string',
//             'id_obat'        => 'required|array',  // Pastikan id_obat adalah array
//             'id_obat.*'      => 'exists:obat,id',  // Validasi bahwa setiap obat ada di database
//             'jumlah_obat'    => 'required|array', // Pastikan jumlah_obat adalah array
//             'jumlah_obat.*'  => 'integer|min:1',  // Validasi setiap jumlah obat minimal 1
//         ]);

//         \Log::info('Data validasi berhasil:', $validated);

//         // Mulai transaksi
//         DB::beginTransaction();

//         // Biaya dokter tetap
//         $biayaDokter = 150000;

//         // Inisialisasi total biaya obat
//         $totalBiayaObat = 0;

//         // Proses setiap obat
//         $obatDetails = [];
//         foreach ($validated['id_obat'] as $index => $idObat) {
//             $obat = Obat::find($idObat);

//             if (!$obat) {
//                 throw new \Exception("Obat dengan ID {$idObat} tidak ditemukan.");
//             }

//             $jumlah = $validated['jumlah_obat'][$index];
//             $subtotal = $obat->harga * $jumlah; // Harga total untuk obat ini

//             $totalBiayaObat += $subtotal;

//             // Tambahkan detail untuk penyimpanan
//             $obatDetails[] = [
//                 'id_obat' => $idObat,
//                 'jumlah' => $jumlah,
//                 'subtotal' => $subtotal,
//             ];
//         }

//         // Hitung total biaya pemeriksaan
//         $biayaPeriksa = $biayaDokter + $totalBiayaObat;

//         // Simpan data ke tabel 'periksa'
//         $periksa = Periksa::create([
//             'id_daftar_poli' => $validated['id_daftar_poli'],
//             'tgl_periksa'    => $validated['tgl_periksa'],
//             'catatan'        => $validated['catatan'],
//             'biaya_periksa'  => $biayaPeriksa,
//         ]);

//         if (!$periksa) {
//             throw new \Exception('Gagal menyimpan data ke tabel "periksa".');
//         }

//         \Log::info('Data Periksa Tersimpan:', $periksa->toArray());

//         // Simpan data ke tabel 'detail_periksa' untuk setiap obat
//         foreach ($obatDetails as $detail) {
//             DetailPeriksa::create([
//                 'id_periksa' => $periksa->id,
//                 'id_obat'    => $detail['id_obat'],
//                 'jumlah'     => $detail['jumlah'],
//                 'subtotal'   => $detail['subtotal'],
//             ]);
//         }

//         \Log::info('Data Detail Periksa Tersimpan:', $obatDetails);

//         // Commit transaksi
//         DB::commit();

//         return redirect()->route('dokter.periksa-pasien')->with('success', 'Data berhasil disimpan!');
//     } catch (\Exception $e) {
//         // Rollback transaksi jika terjadi error
//         DB::rollBack();

//         \Log::error('Terjadi kesalahan:', [
//             'error' => $e->getMessage(),
//             'line'  => $e->getLine(),
//             'file'  => $e->getFile(),
//         ]);

//         return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
//     }
// }

// public function edit($id)
//     {
//         $pasien = DaftarPoli::with('pasien')->findOrFail($id);
//         $obatList = Obat::all();

//         return view('periksa.edit', compact('pasien', 'obatList'));
//     }

//     /**
//      * Simpan data pemeriksaan yang diedit menggunakan fetch API.
//      */
//     public function update(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'id_daftar_poli' => 'required|exists:daftar_polis,id',
//             'tgl_periksa' => 'required|date',
//             'catatan' => 'nullable|string',
//             'id_obat.*' => 'nullable|exists:obats,id',
//             'jumlah_obat.*' => 'nullable|integer|min:1',
//             'biaya_periksa' => 'required|numeric|min:0',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         $daftarPoli = DaftarPoli::findOrFail($request->id_daftar_poli);

//         // Update atau buat data pemeriksaan
//         $periksa = Periksa::updateOrCreate(
//             [
//                 'id_daftar_poli' => $daftarPoli->id,
//             ],
//             [
//                 'tgl_periksa' => $request->tgl_periksa,
//                 'catatan' => $request->catatan,
//                 'biaya_periksa' => $request->biaya_periksa,
//             ]
//         );

//         // Sinkronisasi obat yang diberikan
//         if ($request->has('id_obat')) {
//             $obatData = [];
//             foreach ($request->id_obat as $key => $idObat) {
//                 $obatData[$idObat] = ['jumlah' => $request->jumlah_obat[$key]];
//             }

//             $periksa->obats()->sync($obatData);
//         }

//         return response()->json(['success' => 'Pemeriksaan berhasil diperbarui!']);
//     }

// public function edit($id)
// {
//     $periksa = Periksa::with('pasien', 'obat')->findOrFail($id);
//     $obatList = Obat::all();
//     return response()->json([
//         'periksa' => $periksa,
//         'obatList' => $obatList,
//     ]);
// }

// public function edit($id)
// {
//     try {
//         $periksa = Periksa::with(['pasien', 'detail_periksa.obat'])->findOrFail($id);
//         $obatList = Obat::all(['id', 'nama_obat', 'harga']);
//         // Format detail periksa untuk dikirim ke frontend
//         $detailPeriksa = $periksa->detail_periksa->map(function ($detail) {
//             return [
//                 'id' => $detail->obat->id,
//                 'nama' => $detail->obat->nama,
//                 'harga' => $detail->obat->harga,
//                 'jumlah' => $detail->jumlah, // Pastikan kolom 'jumlah' ada di tabel detail_periksa
//             ];
//         });

//         return response()->json([
//             'pasien' => $periksa->pasien,
//             'tgl_periksa' => $periksa->tgl_periksa,
//             'catatan' => $periksa->catatan,
//             'biaya_periksa' => $periksa->biaya_periksa,
//             'obat' => $periksa->obat,
//             'obatList' => $obatList,
//         ]);
//     } catch (\Exception $e) {
//         return response()->json(['error' => 'Data tidak ditemukan'], 500);
//     }
// }

    // Susun respons JSON secara eksplisit
    // return response()->json([
    //     'id' => $periksa->id,
    //     'pasien' => [
    //         'id' => $periksa->pasien->id,
    //         'nama' => $periksa->pasien->nama,
    //     ],
    //     'tgl_periksa' => $periksa->tgl_periksa,
    //     'catatan' => $periksa->catatan,
    //     'biaya_periksa' => $periksa->biaya_periksa,
    //     'obat' => $periksa->obat->map(function ($obat) {
    //         return [
    //             'id' => $obat->id,
    //             'nama' => $obat->nama,
    //             'harga' => $obat->harga,
    //             'jumlah' => $obat->pivot->jumlah ?? 1, // Pastikan jumlah obat diambil dari tabel pivot
    //         ];
    //     }),
    //     'obatList' => $obatList->map(function ($obat) {
    //         return [
    //             'id' => $obat->id,
    //             'nama' => $obat->nama,
    //             'harga' => $obat->harga,
    //         ];
    //     }),
    // ]);





// public function update(Request $request, $id)
// {
//     try {
//         // Validasi input
//         $validated = $request->validate([
//             'tgl_periksa'   => 'required|date',
//             'catatan'       => 'nullable|string|max:255',
//             'biaya_periksa' => 'required|numeric|min:0',
//             'obat'          => 'nullable|array',
//             'obat.*.id'     => 'exists:obat,id',
//             'obat.*.jumlah' => 'required|integer|min:1',
//         ]);

//         // Cari data periksa berdasarkan ID
//         $periksa = Periksa::find($id);

//         if (!$periksa) {
//             return redirect()->back()->withErrors(['error' => 'Data pemeriksaan tidak ditemukan']);
//         }

//         // Mulai transaksi
//         DB::beginTransaction();

//         // Update data periksa
//         $periksa->update([
//             'tgl_periksa'   => $validated['tgl_periksa'],
//             'catatan'       => $validated['catatan'],
//             'biaya_periksa' => $validated['biaya_periksa'],
//         ]);

//         // Update detail obat jika ada
//         if (isset($validated['obat'])) {
//             // Hapus detail obat yang lama
//             DetailPeriksa::where('id_periksa', $periksa->id)->delete();

//             // Tambahkan detail obat yang baru
//             foreach ($validated['obat'] as $obat) {
//                 $obatModel = Obat::find($obat['id']);
//                 $subtotal = $obatModel->harga * $obat['jumlah'];

//                 DetailPeriksa::create([
//                     'id_periksa' => $periksa->id,
//                     'id_obat'    => $obat['id'],
//                     'jumlah'     => $obat['jumlah'],
//                     'subtotal'   => $subtotal,
//                 ]);
//             }
//         }

//         // Commit transaksi
//         DB::commit();

//         return redirect()->route('dokter.periksa-pasien')->with('success', 'Data pemeriksaan berhasil diperbarui.');
//     } catch (\Exception $e) {
//         // Rollback transaksi jika terjadi kesalahan
//         DB::rollBack();

//         \Log::error('Error saat update data pemeriksaan:', [
//             'error' => $e->getMessage(),
//             'line'  => $e->getLine(),
//             'file'  => $e->getFile(),
//         ]);

//         return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()]);
//     }
// }





}
