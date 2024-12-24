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
        return $this->hasOne(DetailPeriksa::class, 'id_periksa');
    }

}
