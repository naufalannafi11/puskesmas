<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RekamMedis;
use App\Models\Reservasi;
use App\Models\Obat;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        // daftar semua poli
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];

        // siapkan array antrian
        $antrianHariIni = [];

        foreach ($polis as $poli) {

            $antrianHariIni[$poli] = Reservasi::with('pasien', 'dokter')
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['menunggu', 'sedang_diperiksa'])
                ->whereHas('dokter', function ($q) use ($poli) {
                    $q->where('poli', $poli);
                })
                ->orderBy('nomor_antrian', 'asc')
                ->get();
        }

        $now = Carbon::now();

        $hari = $now->dayOfWeekIso; // 1-7
        $bulan = $now->month;

        $prediksi = 0;

        try {
            $response = Http::post('http://127.0.0.1:5000/predict', [
                'hari' => $hari,
                'bulan' => $bulan
            ]);

            $prediksi = 0; // default kalau gagal
            if ($response->successful()) {
                $data = $response->json();
                $prediksi = $data['prediksi'] ?? 0;
            }

        } catch (\Exception $e) {
            $prediksi = 0; // fallback kalau python mati
        }

        return view('dashboard.admin', [
            'totalDokter'      => User::where('role', 'dokter')->count(),
            'totalPasien'      => User::where('role', 'pasien')->count(),
            'totalRekamMedis'  => RekamMedis::count(),
            'totalObat'        => Obat::count(),
            'latestPasien'     => User::where('role', 'pasien')->latest()->take(5)->get(),
            'latestRekamMedis' => RekamMedis::with('pasien')->latest()->take(5)->get(),
            'antrianHariIni'   => $antrianHariIni,

            'prediksi'         => $prediksi
        ]);
    }
}