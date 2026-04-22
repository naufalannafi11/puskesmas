<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $fillable = [
        'nama_obat',
        'jenis',
        'stok',
        'harga'
    ];

    public function rekamMedis()
    {
        return $this->belongsToMany(RekamMedis::class, 'rekam_medis_obat')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
}
