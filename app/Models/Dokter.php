<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    public $timestamps = true; // Pastikan timestamps aktif

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'id_poli',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public static function authenticate($nama, $alamat)
    {
        return self::where('nama', $nama)->where('alamat', $alamat)->first();
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
        }

        return $query;
    }

    public static function getTotalDokter()
    {
        return self::count();
    }

    public function getPasswordAttribute()
    {
        return $this->alamat; // Mengambil password dari kolom alamat
    }


}
