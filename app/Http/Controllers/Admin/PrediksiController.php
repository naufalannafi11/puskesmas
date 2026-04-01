<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'hari' => $request->hari,
            'bulan' => $request->bulan
        ]);

        $hasil = $response->json();

    return back()->with('prediksi', $hasil['prediksi']);
    }
}