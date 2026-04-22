<?php

namespace App\Http\Controllers\Admin;

use App\Models\RekamMedis;
use App\Models\Obat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    // menampilkan daftar yang belum bayar
    public function index()
    {
        $data = RekamMedis::with('pasien','dokter')
            ->where('status', 'belum_bayar')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    // halaman memilih obat
    public function show($id)
    {
        // Ambil rekam medis beserta obat yang diresepkan dokter
        $rekamMedis = RekamMedis::with(['pasien', 'dokter', 'obats'])->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('rekamMedis'));
    }

    // proses pembayaran
    public function bayar(Request $request, $id)
    {
        $rm = RekamMedis::findOrFail($id);
        
        $totalObat = 0;

        // Hitung total harga berdasarkan obat_id dan jumlah (qty) yang dikirim
        if($request->obat_id){
            foreach($request->obat_id as $index => $obatId){
                $obat = Obat::find($obatId);
                $qty = $request->jumlah[$index] ?? 1;
                $totalObat += ($obat->harga * $qty);
            }
        }

        $biayaDokter = 20000; // Biaya periksa tetap

        $total = $biayaDokter + $totalObat;

        $rm->total_bayar = $total;
        $rm->status = 'sudah_bayar';
        $rm->save();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran sebesar Rp ' . number_format($total) . ' berhasil diproses.');
    }
}