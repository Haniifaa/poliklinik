<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'obat';
    public $timestamps = true; // Pastikan timestamps aktif


    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('nama_obat', 'like', "%{$search}%")
                ->orWhere('kemasan', 'like', "%{$search}%");
        }

        return $query;
    }

    // Method untuk menghitung total obat
    public static function getTotalObat()
    {
        return self::count();
    }

    // public function detailPeriksa()
    // {
    //     return $this->belongsToMany(Periksa::class, 'detail_periksa');  // Hapus withPivot('jumlah')
    // }

// App\Models\Obat.php

public function periksa()
{
    return $this->belongsToMany(Periksa::class, 'detail_periksa', 'id_obat', 'id_periksa');
}

}
