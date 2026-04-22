<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamMedis;

class RiwayatController extends Controller
{
    // Index semua riwayat pasien
    public function index()
    {
        $rekamMedis = auth()->user()
            ->rekamMedis() // pastikan ini sesuai di User model
            ->with('dokter')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pasien.riwayat.riwayat', compact('rekamMedis'));
    }

    // Detail satu rekam medis
    public function show($id)
    {
        $rekam_medis = auth()->user()
            ->rekamMedis()
            ->with(['dokter', 'obats'])
            ->findOrFail($id);

        return view('pasien.riwayat.show', compact('rekam_medis'));
    }
}
