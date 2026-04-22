<?php

namespace App\Services;

use App\Models\RekamMedis;
use Carbon\Carbon;

class PrediksiKunjunganService
{
    public function prediksiByPasien($pasienId)
    {
        $kunjungan = RekamMedis::where('pasien_id', $pasienId)
            ->orderBy('tanggal_kunjungan')
            ->pluck('tanggal_kunjungan');

        if ($kunjungan->count() < 2) {
            return null; // data kurang
        }

        $intervals = [];

        for ($i = 1; $i < count($kunjungan); $i++) {
            $tgl1 = Carbon::parse($kunjungan[$i - 1]);
            $tgl2 = Carbon::parse($kunjungan[$i]);

            $intervals[] = $tgl1->diffInDays($tgl2);
        }

        $rata2 = array_sum($intervals) / count($intervals);

        $terakhir = Carbon::parse($kunjungan->last());

        return $terakhir->addDays(round($rata2));
    }
}