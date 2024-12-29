<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPeriksa;
use App\Models\Dokter;


class JadwalPeriksaController extends Controller
{
    // Menampilkan daftar jadwal periksa
    public function index()
    {
        $dokter = session('dokter'); // Ambil data dokter dari session
// Menentukan urutan hari dengan menggunakan urutan manual
$hariUrut = [
    'Senin' => 1,
    'Selasa' => 2,
    'Rabu' => 3,
    'Kamis' => 4,
    'Jumat' => 5,
    'Sabtu' => 6,
    'Minggu' => 7
];

// Mengambil data jadwal dan mengurutkannya berdasarkan hari
$jadwals = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->orderByRaw('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")')
            ->get();
                    $dokters = Dokter::all(); // Mengambil semua data dokter


        return view('dokter.jadwal-periksa', compact('jadwals', 'dokters'));
    }

        // Form tambah jadwal
        public function create()
        {
            $dokter = Dokter::all();
            return view('jadwal.create', compact('dokter'));
        }

        public function store(Request $request)
{
    $request->validate([
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i',
        'status' => 'required|in:Aktif,Tidak Aktif',
    ]);

    $dokter = session('dokter'); // Ambil data dokter dari session

    // Jika status yang dipilih adalah "Aktif"
    if ($request->status == 'Aktif') {
        // Periksa apakah sudah ada jadwal aktif untuk dokter ini
        $existingActive = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->where('status', 'Aktif')
            ->exists();

        if ($existingActive) {
            // Jika sudah ada jadwal aktif, ubah semua jadwal aktif menjadi Tidak Aktif
            JadwalPeriksa::where('id_dokter', $dokter->id)
                ->where('status', 'Aktif')
                ->update(['status' => 'Tidak Aktif']);
        }
    }

    // Buat jadwal baru
    JadwalPeriksa::create([
        'id_dokter' => $dokter->id,
        'hari' => $request->hari,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'status' => $request->status,
    ]);

    return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil ditambahkan.');
}


    public function edit($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        return response()->json($jadwal);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Cari jadwal berdasarkan ID
        $jadwal = JadwalPeriksa::findOrFail($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Perbarui status
        $jadwal->status = $request->status;
        if ($jadwal->save()) {
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan status baru'], 500);
        }
    }

    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        // Validasi data yang diterima
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Menambahkan log debugging untuk memastikan request diterima
        Log::debug('Update request diterima untuk jadwal dengan ID: ' . $jadwalPeriksa->id);

        // // Jika status diubah menjadi Tidak Aktif
        // if ($request->status == 'Tidak Aktif') {
        //     Log::debug('Mengubah status jadwal dengan ID: ' . $jadwalPeriksa->id . ' menjadi Tidak Aktif.');

        //     // Perbarui status menjadi Tidak Aktif
        //     $jadwalPeriksa->update([
        //         'status' => 'Tidak Aktif',
        //     ]);

        //     // Update juga field lain seperti hari, jam mulai, dan jam selesai
        //     $jadwalPeriksa->update([
        //         'hari' => $request->hari,
        //         'jam_mulai' => $request->jam_mulai,
        //         'jam_selesai' => $request->jam_selesai,
        //     ]);
        // }

        // // Jika status diubah menjadi Aktif
        // if ($request->status == 'Aktif') {
        //     Log::debug('Mengubah status jadwal dengan ID: ' . $jadwalPeriksa->id . ' menjadi Aktif.');

        //     // Cek apakah ada jadwal aktif lainnya untuk dokter ini
        //     $existingActive = JadwalPeriksa::where('id_dokter', $jadwalPeriksa->id_dokter)
        //         ->where('status', 'Aktif')
        //         ->where('id', '!=', $jadwalPeriksa->id)  // Jangan cek diri sendiri
        //         ->exists();

        //     // Jika ada jadwal aktif lainnya, ubah statusnya menjadi Tidak Aktif
        //     if ($existingActive) {
        //         Log::debug('Jadwal aktif ditemukan, mengubah statusnya menjadi Tidak Aktif.');

        //         // Ubah status jadwal yang aktif menjadi 'Tidak Aktif'
        //         JadwalPeriksa::where('id_dokter', $jadwalPeriksa->id_dokter)
        //             ->where('status', 'Aktif')
        //             ->update(['status' => 'Tidak Aktif']);
        //     }

            // Update status jadwal yang baru menjadi 'Aktif'
            $jadwalPeriksa->update([
                'status' => 'Aktif',
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);


        // Debugging setelah update untuk memastikan perubahan terjadi
        Log::debug('Jadwal setelah diperbarui:', $jadwalPeriksa->toArray());

        // Redirect setelah pembaruan
        return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil diperbarui.');
    }



    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        $jadwalPeriksa->delete();
        return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil dihapus.');
    }



}
