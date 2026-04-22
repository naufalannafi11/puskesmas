<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Reservasi;

class RekamMedis extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservasi_id',
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
        'rencana_tindak_lanjut',
        'pemeriksaan_lab',
        'status',
        'total_bayar',
    ];

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'rekam_medis_obat')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'kode_icd', 'kode_icd');
    }
}
