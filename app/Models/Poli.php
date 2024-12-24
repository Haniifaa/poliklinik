<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'poli';
    public $timestamps = true; // Pastikan timestamps aktif

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_poli',
        'keterangan',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('nama_obat', 'like', "%{$search}%")
                ->orWhere('kemasan', 'like', "%{$search}%");
        }

        return $query;
    }

    public function dokters()
    {
        return $this->hasMany(Dokter::class, 'id_poli');
    }

    // Method untuk menghitung total poli
    public static function getTotalPoli()
    {
        return self::count();
    }
}
