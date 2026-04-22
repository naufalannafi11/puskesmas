<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class AntreanController extends Controller
{
    public function index()
    {
        // Monitor Antrean Hari Ini
        $reservasis = Reservasi::with(['pasien', 'dokter'])
            ->whereDate('tanggal', today())
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        return view('admin.antrean.index', compact('reservasis'));
    }
}
