<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\RekamMedis;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'tanggal',
        'keluhan',
        'nomor_antrian',
        'status'
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class);
    }
}
