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
        $jadwals = JadwalPeriksa::where('id_dokter', $dokter->id)->get();
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

        $dokter = session('dokter');

        if ($request->status == 'Aktif') {
            $existingActive = JadwalPeriksa::where('id_dokter', $dokter->id)
                ->where('status', 'Aktif')
                ->exists();

            if ($existingActive) {
                return back()->withErrors('Hanya satu jadwal yang boleh aktif dalam seminggu.');
            }
        }

        // Buat jadwal baru dan set id_dokter berdasarkan ID dokter yang login
        JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil ditambahkan.');

    }

    public function edit(JadwalPeriksa $id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        return response()->json($jadwal);
    }

    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $jadwalPeriksa->update($request->all());
        return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        $jadwalPeriksa->delete();
        return redirect()->route('dokter.jadwal-periksa')->with('success', 'Jadwal berhasil dihapus.');
    }


}
