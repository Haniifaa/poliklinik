<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Obat;
use Carbon\Carbon;



class AuthController extends Controller
{
    public function showPasienLogin()
    {
        return view('auth.pasien-login');
    }

    public function showPasienRegister()
    {
        return view('auth.pasien-daftar'); // Ganti dengan path view login Anda
    }

    public function showDokterLogin()
    {
        return view('auth.dokter-login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login'); // Ganti dengan path view login Anda
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Log data input
        Log::info('Admin login attempt', ['email' => $credentials['email']]);

        // Authenticate using the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials)) {
            Log::info('Admin login successful', ['email' => $credentials['email']]);

            // Redirect to the admin dashboard after successful login
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        }

        // Log failed login attempt
        Log::warning('Admin login failed', ['email' => $credentials['email']]);

        return back()->withErrors(['message' => 'Email atau password salah']);

    }

    public function adminDashboard()
    {
        // Check if the admin is logged in using the 'admin' guard
        if (Auth::guard('admin')->check()) {
            // Mengambil jumlah total dari setiap model
         $totalPasien = Pasien::count();
         $totalDokter = Dokter::count();
         $totalPoli = Poli::count();
         $totalObat = Obat::count();        // Data untuk 7 hari terakhir
         $dates = [];
         $totalPasienPerDay = [];
         $totalDokterPerDay = [];
         $totalPoliPerDay = [];
         $totalObatPerDay = [];

         // Ambil data untuk 7 hari terakhir
         for ($i = 6; $i >= 0; $i--) {
             $date = Carbon::today()->subDays($i)->format('Y-m-d');
             $dates[] = $date;

             // Hitung total pasien, dokter, poli, dan obat untuk masing-masing tanggal
             $totalPasienPerDay[] = Pasien::whereDate('created_at', $date)->count();
             $totalDokterPerDay[] = Dokter::whereDate('created_at', $date)->count();
             $totalPoliPerDay[] = Poli::whereDate('created_at', $date)->count();
             $totalObatPerDay[] = Obat::whereDate('created_at', $date)->count();
         }

         // Kirim data ke view
         return view('admin.dashboard', compact(
             'totalPasien', 'totalDokter', 'totalPoli', 'totalObat',
             'dates', 'totalPasienPerDay', 'totalDokterPerDay',
             'totalPoliPerDay', 'totalObatPerDay'
         ));

        }

        return redirect()->route('admin.login')->withErrors(['message' => 'Harap login terlebih dahulu']);
    }


    public function logindokter(Request $request)
    {

        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $dokter = Dokter::authenticate($request->nama, $request->alamat);

        if ($dokter) {
            // Simpan data dokter ke sesi
            session(['dokter' => $dokter]);
            return redirect()->route('dokter.dashboard')->with('success', 'Login berhasil');
        }

        return back()->withErrors(['message' => 'Nama atau password salah']);


    }

    public function loginpasien(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $pasien = Pasien::authenticate($request->nama, $request->alamat);

        if ($pasien) {
            // Simpan data dokter ke sesi
            session(['pasien' => $pasien]);
            return redirect()->route('pasien.dashboard')->with('success', 'Login berhasil');
        }

        return back()->withErrors(['message' => 'Nama atau password salah']);

        // $request->validate([
        //     'nama' => 'required|string',
        //     'alamat' => 'required|string',
        // ]);

        // $pasien = Pasien::authenticate($request->nama, $request->alamat);

        // if ($pasien) {
        //     // Simpan data dokter ke sesi
        //     session(['pasien' => $pasien]);
        //     return redirect()->route('pasien.dashboard')->with('success', 'Login berhasil');
        // }

        // return back()->withErrors(['message' => 'Nama atau alamat salah']);
    }


    public function dashboard()
    {
        // Pengecekan apakah ada data dokter di sesi
        $dokter = session('dokter');
        if (!$dokter) {
            return redirect()->route('login.dokter')->withErrors(['message' => 'Harap login terlebih dahulu']);
        }

        // Tampilkan dashboard
        return view('dokter.dashboard', ['dokter' => $dokter]);
    }
}
