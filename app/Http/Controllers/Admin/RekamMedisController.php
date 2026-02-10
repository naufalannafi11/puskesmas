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

        return view('admin.rekam_medis.create', compact('pasiens','dokters'));
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

        RekamMedis::create([
            'pasien_id'   => $request->pasien_id,
            'dokter_id'   => $request->dokter_id,
            'tanggal'     => $request->tanggal,
            'anamnesis'   => $request->anamnesis,
            'pemeriksaan' => $request->pemeriksaan,
            'diagnosis'   => $request->diagnosis,
            'icd10'       => $request->icd10,
            'tindakan'    => $request->tindakan,
            'pengobatan'  => $request->pengobatan,
        ]);

        return redirect()->route('admin.rekam-medis.index')
            ->with('success', 'Rekam medis berhasil ditambahkan');
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

        return redirect()->route('admin.rekam-medis.index')
            ->with('success', 'Rekam medis diperbarui');
    }

    /**
     * HAPUS
     */
    public function destroy(RekamMedis $rekamMedi)
    {
        $rekamMedi->delete();

        return back()->with('success', 'Rekam medis dihapus');
    }
}
