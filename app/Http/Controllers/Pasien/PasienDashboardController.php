<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use App\Models\User;

class PasienDashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $reservasis = Reservasi::where('pasien_id', Auth::id())
            ->latest()
            ->get();

        // daftar poli
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];
        $antrianPoli = [];

        foreach ($polis as $poli) {
            $antrianPoli[$poli] = Reservasi::whereDate('tanggal', $today)
                ->where('status', 'menunggu')
                ->whereHas('dokter', function ($q) use ($poli) {
                    $q->where('poli', $poli);
                })
                ->orderBy('nomor_antrian', 'asc')
                ->first();
        }

        // antrian pasien sendiri - Gunakan whereDate dan ambil yang terbaru hari ini
        $antrianSaya = Reservasi::whereDate('tanggal', $today)
            ->where('pasien_id', Auth::id())
            ->where('status', 'menunggu')
            ->latest()
            ->first();

        return view('dashboard.pasien', compact(
            'reservasis',
            'antrianPoli',
            'antrianSaya'
        ));
    }
}