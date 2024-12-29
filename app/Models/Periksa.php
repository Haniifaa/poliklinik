<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Periksa extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'periksa';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa'
    ];


    // Definisikan relasi ke tabel 'daftar_poli'
    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    // Menentukan format tanggal yang diinginkan
    protected $dates = ['tgl_periksa'];

    // Jika Anda ingin menggunakan timestamps secara otomatis
    public $timestamps = true;


    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa', 'id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id');
    }

    // Periksa model relasi
    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'detail_periksa', 'id_periksa', 'id_obat');
    }


    // public function obat()
    // {
    //     return $this->belongsToMany(Obat::class, 'periksa_obat', 'id_periksa', 'id_obat')
    //         ->withPivot('jumlah'); // jika ada kolom tambahan di tabel pivot seperti 'jumlah'
    // }

}
