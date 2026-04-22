<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        $today = date('Y-m-d');

        // 1. STATISTIK ANTREAN HARI INI
        $stats = [
            'menunggu' => Reservasi::where('dokter_id', $doctorId)->whereDate('tanggal', $today)->where('status', 'menunggu')->count(),
            'diperiksa' => Reservasi::where('dokter_id', $doctorId)->whereDate('tanggal', $today)->where('status', 'sedang_diperiksa')->count(),
            'selesai' => Reservasi::where('dokter_id', $doctorId)->whereDate('tanggal', $today)->where('status', 'selesai')->count(),
        ];

        // 2. RIWAYAT PEMERIKSAAN TERBARU (5)
        $latestRekamMedis = RekamMedis::where('dokter_id', $doctorId)
            ->with('pasien')
            ->latest()
            ->take(5)
            ->get();

        // 3. ANALISIS TOP DIAGNOSA DOKTER (Sesuai histori praktik)
        $topDiagnoses = RekamMedis::where('dokter_id', $doctorId)
            ->select('diagnosis', DB::raw('count(*) as total'))
            ->whereNotNull('diagnosis')
            ->groupBy('diagnosis')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        // 4. PREDIKSI KEPADATAN AI (Integrasi Flask)
        $aiResult = null;
        try {
            $response = Http::post('http://127.0.0.1:5050/predict', [
                'start_date' => $today
            ]);

            if ($response->successful()) {
                $aiResult = $response->json();
            }
        } catch (\Exception $e) {
            $aiResult = null;
        }

        return view('dashboard.dokter', [
            // Statistik Global (untuk konsistensi layout)
            'totalDokter' => \App\Models\User::where('role', 'dokter')->count(),
            'totalPasien' => \App\Models\User::where('role', 'pasien')->count(),
            'totalRekamMedis' => RekamMedis::count(),
            'totalObat' => \App\Models\Obat::count(),
            
            // Data Terkait Dokter
            'stats' => $stats,
            'latestPasien' => \App\Models\User::where('role', 'pasien')->latest()->take(5)->get(),
            'latestRekamMedis' => $latestRekamMedis,
            'antrianHariIni' => [\Auth::user()->poli => Reservasi::where('dokter_id', $doctorId)->whereDate('tanggal', $today)->with('pasien', 'dokter')->get()],
            'prediksi' => $aiResult['prediksi'] ?? 0,
            'smartInsights' => [], // Dokter mungkin tidak perlu stock warnings admin
            'topDiagnoses' => $topDiagnoses,
        ]);
    }
}
