<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalPeriksaController;
use App\Http\Controllers\PeriksaPasienController;
use App\Http\Controllers\RiwayatPasienController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\DaftarPoliController;
use App\Http\Controllers\PeriksaController;
use App\Http\Controllers\DetailPeriksaController;










Route::get('/', function () {
    return view('homepage', ['title' => 'Home Page']);
});


Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/masterdata/pasien', [PasienController::class, 'index'])->name('admin.masterdata.pasien');
Route::get('/admin/masterdata/dokter', [DokterController::class, 'index'])->name('admin.masterdata.dokter');
Route::get('/admin/masterdata/obat', [ObatController::class, 'index'])->name('admin.masterdata.obat');
Route::get('/admin/masterdata/poli', [PoliController::class, 'index'])->name('admin.masterdata.poli');

Route::prefix('admin')->group(function () {
    Route::get('pasien/create', [PasienController::class, 'create'])->name('admin.pasien.create');
    Route::post('pasien', [PasienController::class, 'store'])->name('admin.pasien.store');
    Route::get('pasien/{pasien}/edit', [PasienController::class, 'edit'])->name('admin.pasien.edit');
    Route::put('pasien/{pasien}', [PasienController::class, 'update'])->name('admin.pasien.update');
    Route::delete('pasien/{pasien}', [PasienController::class, 'destroy'])->name('admin.pasien.destroy');

    Route::get('dokter/create', [DokterController::class, 'create'])->name('admin.dokter.create');
    Route::post('dokter', [DokterController::class, 'store'])->name('admin.dokter.store');
    Route::get('dokter/{dokter}/edit', [DokterController::class, 'edit'])->name('admin.dokter.edit');
    Route::put('dokter/{dokter}', [DokterController::class, 'update'])->name('admin.dokter.update');
    Route::delete('dokter/{dokter}', [DokterController::class, 'destroy'])->name('admin.dokter.destroy');

    Route::get('poli/create', [PoliController::class, 'create'])->name('admin.poli.create');
    Route::post('poli', [PoliController::class, 'store'])->name('admin.poli.store');
    Route::get('poli/{poli}/edit', [PoliController::class, 'edit'])->name('admin.poli.edit');
    Route::put('poli/{poli}', [PoliController::class, 'update'])->name('admin.poli.update');
    Route::delete('poli/{poli}', [PoliController::class, 'destroy'])->name('admin.poli.destroy');

    Route::get('obat/create', [ObatController::class, 'create'])->name('admin.obat.create');
    Route::post('obat', [ObatController::class, 'store'])->name('admin.obat.store');
    Route::get('obat/{obat}/edit', [ObatController::class, 'edit'])->name('admin.obat.edit');
    Route::put('obat/{obat}', [ObatController::class, 'update'])->name('admin.obat.update');
    Route::delete('obat/{obat}', [ObatController::class, 'destroy'])->name('admin.obat.destroy');
});


// Rute untuk halaman admin dokter, hanya bisa diakses setelah login
Route::get('/login/dokter', [AuthController::class, 'showDokterLogin'])->name('login.dokter');
Route::post('/dokter/login', [AuthController::class, 'logindokter'])->name('auth.login.submit');
Route::get('/dokter/dashboard', [AuthController::class, 'dashboard'])->name('dokter.dashboard');
// Route::get('/dokter/dashboard', [DokterController::class, 'index'])->name('dokter.dashboard');
Route::get('/dokter/jadwal-periksa', [JadwalPeriksaController::class, 'index'])->name('dokter.jadwal-periksa');
Route::post('dokter/jadwal-periksa-store', [JadwalPeriksaController::class, 'store'])->name('dokter.jadwal-periksa.store');
Route::delete('/jadwal-periksa/{jadwal_periksa}', [JadwalPeriksaController::class, 'destroy'])->name('dokter.jadwal-periksa.destroy');
Route::get('dokter/jadwal-periksa/{jadwal_periksa}/edit', [JadwalPeriksaController::class, 'edit'])->name('dokter.jadwal-periksa.edit');
Route::put('dokter/jadwal-periksa/{jadwal_periksa}', [JadwalPeriksaController::class, 'update'])->name('dokter.jadwal-periksa.update');


Route::get('/dokter/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('dokter.periksa-pasien');
Route::get('/dokter/periksa-pasien/periksa/{periksa}', [PeriksaPasienController::class, 'periksa'])->name('dokter.periksa');
Route::post('/dokter/periksa-pasien/periksa', [PeriksaPasienController::class, 'store'])->name('periksa.store');
Route::get('/dokter/periksa-pasien/{periksa}/edit', [PeriksaPasienController::class, 'edit'])->name('periksa.edit');
Route::put('/dokter/periksa-pasien/{periksa}', [PeriksaPasienController::class, 'update'])->name('dokter.periksa.update');
Route::get('/dokter/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('dokter.riwayat-pasien');
Route::get('/dokter/profile/', [DokterController::class, 'profile'])->name('dokter.profile');
Route::get('/dokter/profile/edit', [DokterController::class, 'profileEdit'])->name('dokter.edit');
Route::put('/dokter/profile/update/{dokter}', [DokterController::class, 'profileUpdate'])->name('dokter.update');
Route::post('/dokter/jadwal-periksa/{jadwal_periksa}/update-status', [JadwalPeriksaController::class, 'updateStatus']);


Route::get('/login/pasien', [AuthController::class, 'showPasienLogin'])->name('login.pasien');
Route::post('pasien/login', [AuthController::class, 'loginpasien'])->name('pasien.login');
Route::get('/daftar/pasien', [AuthController::class, 'showPasienRegister'])->name('daftar.pasien');
Route::post('/register', [PasienController::class, 'register'])->name('pasien.register');
Route::post('/check/pasien', [PasienController::class, 'checkExisting']);
Route::post('/pasien/daftar-poli', [DaftarPoliController::class, 'store'])->name('pasien.daftar-poli');

Route::get('/pasien/dashboard', [PasienController::class, 'dashboard'])->name('pasien.dashboard');
Route::get('/pasien/poli', [DaftarPoliController::class, 'showPoliPasien'])->name('pasien.poli');

Route::post('/logout/dokter', [AuthController::class, 'logoutdokter'])->name('dokter.logout');
Route::post('/logout/pasien', [AuthController::class, 'logoutpasien'])->name('pasien.logout');

