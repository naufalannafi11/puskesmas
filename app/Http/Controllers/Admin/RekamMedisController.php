<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    /**
     * TAMPILKAN LIST REKAM MEDIS
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $rekamMedis = RekamMedis::with(['pasien','dokter'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pasien', function ($qp) use ($search) {
                    $qp->where('name', 'like', "%$search%")
                       ->orWhere('no_rm', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.rekam_medis.index', compact('rekamMedis', 'search'));
    }

    /**
     * FORM TAMBAH REKAM MEDIS
     */
    public function create()
    {
        $pasiens = User::where('role', 'pasien')->get();
        $dokters = User::where('role', 'dokter')->get();
        $obats   = \App\Models\Obat::orderBy('nama_obat')->get();

        return view('admin.rekam_medis.create', compact('pasiens','dokters','obats'));
    }

    /**
     * SIMPAN DATA
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required',
            'dokter_id' => 'required',
            'tanggal'   => 'required|date',
        ]);

        \DB::beginTransaction();

        try {
            $rekamMedis = RekamMedis::create([
                'pasien_id'   => $request->pasien_id,
                'dokter_id'   => $request->dokter_id,
                'tanggal'     => $request->tanggal,
                'anamnesis'   => $request->anamnesis,
                'pemeriksaan' => $request->pemeriksaan,
                'diagnosis'   => $request->diagnosis,
                'kode_icd'    => $request->kode_icd,
                'tindakan'    => $request->tindakan,
                'pengobatan'  => 'Resep Terstruktur (Lihat Detail)',
            ]);

            // Simpan Obat & Kurangi Stok
            if ($request->has('obat_ids')) {
                foreach ($request->obat_ids as $index => $obatId) {
                    if (!$obatId) continue;
                    
                    $jumlah = $request->jumlahs[$index] ?? 1;
                    $obat = \App\Models\Obat::findOrFail($obatId);

                    if ($obat->stok < $jumlah) {
                        throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi (Sisa: {$obat->stok})");
                    }

                    // Hubungkan ke Pivot
                    $rekamMedis->obats()->attach($obatId, ['jumlah' => $jumlah]);

                    // Kurangi Stok Rill
                    $obat->decrement('stok', $jumlah);
                }
            }

            \DB::commit();

            return redirect()->route('admin.rekam_medis.index')
                ->with('success', 'Rekam medis berhasil ditambahkan dan stok obat dikurangi.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * FORM EDIT
     */
    public function edit(RekamMedis $rekamMedi)
    {
        $pasiens = User::where('role','pasien')->get();
        $dokters = User::where('role','dokter')->get();

        return view('admin.rekam_medis.edit', compact('rekamMedi','pasiens','dokters'));
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, RekamMedis $rekamMedi)
    {
        $rekamMedi->update($request->all());

        return redirect()->route('admin.rekam_medis.index')
            ->with('success', 'Rekam medis diperbarui');
    }

    /**
     * HAPUS
     */
    public function destroy(RekamMedis $rekamMedi)
    {
        \DB::beginTransaction();

        try {
            // Kembalikan Stok (Restitusi)
            foreach ($rekamMedi->obats as $obat) {
                $jumlah = $obat->pivot->jumlah;
                $obat->increment('stok', $jumlah);
            }

            $rekamMedi->delete();

            \DB::commit();
            return back()->with('success', 'Rekam medis dihapus dan stok obat dikembalikan.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function show($id)
{
    $rekamMedi = RekamMedis::with(['pasien','dokter'])
        ->findOrFail($id);

    return view('admin.rekam_medis.show', compact('rekamMedi'));
}
}
