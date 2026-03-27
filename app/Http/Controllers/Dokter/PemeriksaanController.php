<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
{
    /**
     * Menampilkan daftar pasien hari ini yang statusnya menunggu
     */
    public function index()
{
    $reservasis = Reservasi::where('dokter_id', Auth::id())
        ->where('status', 'menunggu')
        ->whereDate('tanggal', today())
        ->with('pasien')
        ->orderBy('nomor_antrian', 'asc')
        ->get();

    return view('dokter.pemeriksaan.index', compact('reservasis'));
}

    /**
     * Form pemeriksaan
     */
    public function create(Reservasi $reservasi)
    {
        // 🔒 Pastikan hanya dokter yang bersangkutan yang bisa akses
        if ($reservasi->dokter_id != Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        return view('dokter.pemeriksaan.create', compact('reservasi'));
    }

    /**
     * Simpan hasil pemeriksaan
     */
    public function store(Request $request, Reservasi $reservasi)
    {
        // 🔒 Cek keamanan lagi
        if ($reservasi->dokter_id != Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'anamnesis'   => 'required|string',
            'pemeriksaan' => 'required|string',
            'diagnosis'   => 'required|string',
            'kode_icd'    => 'nullable|string',
            'tindakan'    => 'nullable|string',
            'pengobatan'  => 'nullable|string',
            'rujukan'     => 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'pemeriksaan_lab' => 'nullable|string',
        ]);

        RekamMedis::create([
            'reservasi_id' => $reservasi->id,
            'pasien_id'    => $reservasi->pasien_id,
            'dokter_id'    => Auth::id(),
            'tanggal'      => now(),
            'anamnesis'    => $request->anamnesis,
            'pemeriksaan'  => $request->pemeriksaan,
            'diagnosis'    => $request->diagnosis,
            'kode_icd'     => $request->kode_icd,
            'tindakan'     => $request->tindakan,
            'pengobatan'   => $request->pengobatan,
            'rujukan'      => $request->rujukan,
            'rencana_tindak_lanjut' => $request->rencana_tindak_lanjut,
            'pemeriksaan_lab' => $request->pemeriksaan_lab,
        ]);

        // Update status reservasi
        $reservasi->update([
            'status' => 'selesai'
        ]);

        return redirect()
            ->route('dokter.pemeriksaan.index')
            ->with('success', 'Pemeriksaan berhasil disimpan');
    }

    public function riwayat()
{
    $rekamMedis = RekamMedis::where('dokter_id', Auth::id())
        ->with('reservasi.pasien')
        ->latest()
        ->get();

    return view('dokter.pemeriksaan.riwayat', compact('rekamMedis'));
}

public function show($id)
{
    $rekam_medis = \App\Models\RekamMedis::with('pasien')
        ->findOrFail($id);

    return view('dokter.pemeriksaan.show', compact('rekam_medis'));
}

}
