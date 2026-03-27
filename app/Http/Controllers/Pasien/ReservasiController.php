<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use App\Models\User;
use App\Models\RekamMedis;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::where('pasien_id', Auth::id())
            ->latest()
            ->get();

        return view('pasien.reservasi.index', compact('reservasis'));
    }

    public function create()
    {
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];
        return view('pasien.reservasi.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'poli' => 'required',
            'dokter_id' => 'required',
            'tanggal'   => 'required|date',
            'keluhan'   => 'required'
        ]);

        // Ambil nomor antrian terakhir untuk dokter + tanggal tertentu
        $lastAntrian = Reservasi::where('tanggal', $request->tanggal)
            ->whereHas('dokter', function ($q) use ($request){
                $q->where('poli', $request->poli);
            })
            ->max('nomor_antrian');

        $nomorAntrian = $lastAntrian ? $lastAntrian + 1 : 1;

        // Simpan reservasi
        Reservasi::create([
    'pasien_id' => Auth::id(),
    'dokter_id' => $request->dokter_id,
    'tanggal'   => $request->tanggal,
    'keluhan'   => $request->keluhan,
    'nomor_antrian' => $nomorAntrian,
    'status' => 'menunggu'
]);

        return redirect()->route('pasien.reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat. Nomor antrian: ' . $nomorAntrian);
    }



    public function rekamMedis()
{
    $rekamMedis = RekamMedis::where('pasien_id', auth()->id())
        ->with('dokter')
        ->latest()
        ->get();

    return view('pasien.rekam_medis.index', compact('rekamMedis'));
}

}
