<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model
{
    use HasFactory;

    protected $table = 'daftar_poli';
    protected $fillable = ['id_pasien', 'id_jadwal', 'keluhan', 'no_antrian'];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal', 'id');
    }

        // Mengakses dokter melalui jadwal
    public function dokter()
    {
        return $this->hasOneThrough(Dokter::class, JadwalPeriksa::class, 'id', 'id', 'id_jadwal', 'id_dokter');
    }

    public function poli()
    {
        return $this->hasOneThrough(Poli::class, Dokter::class, 'id', 'id', 'id_dokter', 'id_poli');
    }

    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli', 'id');
    }
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa', 'id');
    }


}
