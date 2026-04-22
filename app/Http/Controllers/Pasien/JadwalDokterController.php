<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];
        $selectedPoli = $request->poli;
        
        $jadwals = [];
        if ($selectedPoli) {
            $jadwals = JadwalDokter::with('dokter')
                ->where('poli', $selectedPoli)
                ->orderBy(rawOrderDay())
                ->get();
        }

        return view('pasien.jadwal.index', compact('jadwals', 'polis', 'selectedPoli'));
    }
}

/**
 * Helper logic untuk mengurutkan hari secara manual agar Senin-Sabtu berurutan
 */
function rawOrderDay() {
    return \Illuminate\Support\Facades\DB::raw("CASE 
        WHEN hari = 'Senin' THEN 1 
        WHEN hari = 'Selasa' THEN 2 
        WHEN hari = 'Rabu' THEN 3 
        WHEN hari = 'Kamis' THEN 4 
        WHEN hari = 'Jumat' THEN 5 
        WHEN hari = 'Sabtu' THEN 6 
        WHEN hari = 'Minggu' THEN 7 
        ELSE 8 END");
}
