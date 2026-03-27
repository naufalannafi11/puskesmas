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

    $dokters = User::where('role','dokter')->get();

    $reservasis = Reservasi::where('pasien_id', Auth::id())
        ->latest()
        ->get();

    // antrian sedang dilayani
    $antrianSekarang = Reservasi::whereDate('tanggal', $today)
        ->where('status','menunggu')
        ->orderBy('nomor_antrian','asc')
        ->first();

    // daftar poli
    $polis = ['Umum','Gigi','KIA','KB','Imunisasi'];

    $antrianPoli = [];

    foreach ($polis as $poli) {

        $antrianPoli[$poli] = Reservasi::whereDate('tanggal', $today)
            ->where('status','menunggu')
            ->whereHas('dokter', function($q) use ($poli){
                $q->where('poli', $poli);
            })
            ->orderBy('nomor_antrian','asc')
            ->first();
    }

    // antrian pasien sendiri
    $antrianSaya = Reservasi::where('tanggal', $today)
        ->where('pasien_id', Auth::id())
        ->where('status','menunggu')
        ->first();

    return view('dashboard.pasien', compact(
        'dokters',
        'reservasis',
        'antrianPoli',
        'antrianSaya',
        'antrianSekarang'
    ));
}
}