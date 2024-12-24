<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    public $timestamps = true; // Pastikan timestamps aktif


    protected $fillable = [
        'nama',
        'alamat',
        'no_ktp',
        'no_hp',
        'no_rm',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
        }

        return $query;
    }

    // Method untuk menghitung total pasien
    public static function getTotalPasien()
    {
        return self::count();
    }

    public static function authenticate($nama, $alamat)
    {
        return self::where('nama', $nama)
                   ->where('alamat', $alamat)
                   ->first();
    }


}
