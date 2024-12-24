<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Poli;
use App\Models\Obat;

class AdminController extends Controller
{
    public function index()
    {
        //  // Mengambil jumlah total dari setiap model
        //  $totalPasien = Pasien::count();
        //  $totalDokter = Dokter::count();
        //  $totalPoli = Poli::count();
        //  $totalObat = Obat::count();

        //  dd($totalPasien, $totalDokter, $totalPoli, $totalObat);


        //  // Kirim data ke tampilan
        //  return view('admin.dashboard', compact('totalPasien', 'totalDokter', 'totalPoli', 'totalObat'));

    }

    public function pasien()
    {
        return view('admin.masterdata.pasien'); // Pastikan kamu memiliki view 'dokter_dashboard'
    }

    public function dokter()
    {
        return view('admin.masterdata.dokter'); // Pastikan kamu memiliki view 'dokter_dashboard'
    }

    public function obat()
    {
        return view('admin.masterdata.obat'); // Pastikan kamu memiliki view 'dokter_dashboard'
    }

    public function poli()
    {
        return view('admin.masterdata.poli'); // Pastikan kamu memiliki view 'dokter_dashboard'
    }


}

