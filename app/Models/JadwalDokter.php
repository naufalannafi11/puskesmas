<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokters';

    protected $fillable = [
        'dokter_id',
        'poli',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
}
