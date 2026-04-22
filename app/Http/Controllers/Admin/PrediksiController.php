<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrediksiController extends Controller
{
    // tampil halaman
    public function index()
    {
        return view('admin.prediksi.index');
    }

    // proses prediksi
    public function predict(Request $request)
    {
        // Validasi
        $request->validate([
            'start_date' => 'required|date'
        ]);

        try {
            // Panggil Flask di port 5000 (EIS Forecast)
            $response = Http::timeout(10)->post('http://127.0.0.1:5050/predict', [
                'start_date' => $request->start_date
            ]);

            if ($response->successful()) {
                $hasil = $response->json();

                // --- SMART INSIGHTS (LARAVEL SIDE) ---
                $smartInsights = [];
                $avgDemand = $hasil['summary']['avg_demand'] ?? 0;
                
                // 1. Cek Stok Obat Berdasarkan Ramalan Beban
                $stokKritis = \App\Models\Obat::where('stok', '<', 30)->get();
                if ($avgDemand > 10 && $stokKritis->count() > 0) {
                    foreach ($stokKritis as $obat) {
                        $smartInsights[] = "PERINGATAN: Stok {$obat->nama_obat} tersisa {$obat->stok}. Segera restock untuk mengantisipasi lonjakan pasien.";
                    }
                }

                // 2. Saran Jadwal Dokter
                if ($avgDemand > 23) {
                    $smartInsights[] = "REKOMENDASI: Tambah shift jadwal dokter untuk mengimbangi beban pasien > 23 orang/hari.";
                }

                // Gabungkan Insight dari Flask & Laravel
                $hasil['summary']['insights'] = array_merge($hasil['summary']['insights'], $smartInsights);
                
                return back()->with([
                    'success' => 'Prediksi berhasil dihitung menggunakan metode EIS.',
                    'hasil'   => $hasil
                ]);
            }

            return back()->with('error', 'Gagal terhubung ke modul AI. Pastikan server Flask sudah berjalan di port 5050.');

        } catch (\Exception $e) {
            Log::error('AI Prediction Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}