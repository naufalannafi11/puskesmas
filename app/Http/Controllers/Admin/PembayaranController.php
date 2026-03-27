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
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('data'));
    }

    // halaman memilih obat
    public function show($id)
    {
        $rekamMedis = RekamMedis::with('pasien','dokter')->findOrFail($id);
        $obat = Obat::all();

        return view('admin.pembayaran.show', compact('rekamMedis','obat'));
    }

    // proses pembayaran
    public function bayar(Request $request, $id)
    {
        $rm = RekamMedis::findOrFail($id);

        $totalObat = 0;

        if($request->obat_id){
            foreach($request->obat_id as $obatId){
                $obat = Obat::find($obatId);
                $totalObat += $obat->harga;
            }
        }

        $biayaDokter = 20000; // contoh biaya poli

        $total = $biayaDokter + $totalObat;

        $rm->total_bayar = $total;
        $rm->status = 'sudah_bayar';
        $rm->save();

        return redirect()->route('admin.pembayaran.index')
            ->with('success','Pembayaran berhasil');
    }
}