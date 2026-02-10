<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RekamMedis;
use App\Models\Obat;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.admin', [
            'totalDokter' => User::where('role', 'dokter')->count(),
            'totalPasien' => User::where('role', 'pasien')->count(),
            'totalRekamMedis' => RekamMedis::count(),
            

            'latestPasien' => User::where('role','pasien')
                                ->latest()
                                ->take(5)
                                ->get(),

            'latestRekamMedis' => collect(),

        ]);
    }
}
