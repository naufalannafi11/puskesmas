<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. FILTER TANGGAL (Default: Bulan Ini)
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();

        // 2. DATA KUNJUNGAN PASIEN (Line Chart Data)
        $kunjunganData = Reservasi::select(DB::raw('DATE(tanggal) as date'), DB::raw('count(*) as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. DATA PENDAPATAN KASIR (Area Chart Data)
        $pendapatanData = RekamMedis::select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(total_bayar) as total'))
            ->where('status', 'sudah_bayar')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $totalPendapatan = $pendapatanData->sum('total');

        // 4. DATA PENYAKIT TERBANYAK (Bar Chart Data)
        $penyakitTerbanyak = RekamMedis::select('kode_icd', DB::raw('count(*) as jumlah'))
            ->whereNotNull('kode_icd')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('kode_icd')
            ->orderBy('jumlah', 'desc')
            ->take(10)
            ->with('penyakit')
            ->get();

        // 5. PENYIAPAN OBJEK UNTUK CHART.JS
        $chartKunjungan = [
            'labels' => $kunjunganData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
            'values' => $kunjunganData->pluck('total')->toArray()
        ];

        $chartPendapatan = [
            'labels' => $pendapatanData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
            'values' => $pendapatanData->pluck('total')->toArray()
        ];

        return view('admin.laporan.index', compact(
            'startDate', 'endDate', 
            'chartKunjungan', 'chartPendapatan', 
            'penyakitTerbanyak', 'totalPendapatan',
            'kunjunganData'
        ));
    }
}
