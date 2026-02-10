<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RekamMedis extends Model
{
    protected $fillable = [
        
        'pasien_id',
        'dokter_id',
        'tanggal',
        'anamnesis',
        'pemeriksaan',
        'diagnosis',
        'kode_icd',
        'tindakan',
        'pengobatan',
        'rujukan',
    ];

    public function pasien(){
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function dokter(){
        return this->belongTo(User::class, 'dokter_id');
    }
}
