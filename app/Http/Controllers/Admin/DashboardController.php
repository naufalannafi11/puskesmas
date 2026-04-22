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

        // ================= POLI + ANTRIAN =================
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];
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

        // ================= INIT DATA =================
        $aiResult = null;
        $smartInsights = [];

        try {
            // ======================================================
            // 🧠 MODEL UNIFIED: PREDIKSI BEBAN & INTERVAL
            // ======================================================
            $response = Http::post('http://127.0.0.1:5050/predict', [
                'start_date' => $today
            ]);

            if ($response->successful()) {
                $aiResult = $response->json();
                
                // --- SMART INSIGHTS (LARAVEL SIDE) ---
                $avgDemand = $aiResult['summary']['avg_demand'] ?? 0;
                
                // 1. Cek Stok Obat Berdasarkan Ramalan Beban
                $obatKritis = Obat::where('stok', '<', 30)->get();
                if ($avgDemand > 10 && $obatKritis->count() > 0) {
                    foreach ($obatKritis as $o) {
                        $smartInsights[] = [
                            'type' => 'warning',
                            'message' => "Stok {$o->nama_obat} rendah ({$o->stok}). Segera restock sebelum lonjakan pasien."
                        ];
                    }
                }

                // 2. Rekomendasi Jadwal
                if ($avgDemand > 23) {
                    $smartInsights[] = [
                        'type' => 'info',
                        'message' => "Rekomendasi: Tambah shift dokter untuk mengimbangi prediksi beban tinggi (>23 pasien/hari)."
                    ];
                }
            }
        } catch (\Exception $e) {
            $aiResult = null;
        }

        // ================= RETURN VIEW =================
        return view('dashboard.admin', [
            'totalDokter' => User::where('role', 'dokter')->count(),
            'totalPasien' => User::where('role', 'pasien')->count(),
            'totalRekamMedis' => RekamMedis::count(),
            'totalObat' => Obat::count(),
            'latestPasien' => User::where('role', 'pasien')->latest()->take(5)->get(),
            'latestRekamMedis' => RekamMedis::with('pasien')->latest()->take(5)->get(),
            'antrianHariIni' => $antrianHariIni,
            'prediksi' => $aiResult['prediksi'] ?? 0,
            'smartInsights' => $smartInsights
        ]);
    }
}