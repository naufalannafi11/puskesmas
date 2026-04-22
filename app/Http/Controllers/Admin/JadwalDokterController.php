<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalDokter;
use App\Models\User;

class JadwalDokterController extends Controller
{
    // ================= LIST =================
    public function index()
    {
        $jadwals = JadwalDokter::with('dokter')->latest()->get();
        $dokters = User::where('role', 'dokter')->get();

        return view('admin.jadwal.index', compact('jadwals', 'dokters'));
    }

    // ================= SIMPAN =================
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required',
            'poli'        => 'required',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        // 🔥 CEK BENTROK JADWAL
        $cek = JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })
            ->exists();

        if ($cek) {
            return back()->with('error', 'Jadwal bentrok!');
        }

        JadwalDokter::create($request->all());

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $dokters = User::where('role', 'dokter')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'dokters'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        $request->validate([
            'dokter_id'   => 'required',
            'poli'        => 'required',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal-dokter.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        JadwalDokter::findOrFail($id)->delete();

        return back()->with('success', 'Jadwal berhasil dihapus');
    }
}