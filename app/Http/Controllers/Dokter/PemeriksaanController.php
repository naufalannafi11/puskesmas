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
        // 🕒 AUTOMATION: Pindahkan pasien yang "lewat" (3 menit tidak muncul) ke akhir antrian
        $timedOutPatients = Reservasi::where('dokter_id', Auth::id())
            ->where('status', 'menunggu')
            ->whereDate('tanggal', today())
            ->whereNotNull('called_at')
            ->where('called_at', '<', now()->subMinutes(3))
            ->get();

        foreach ($timedOutPatients as $patient) {
            // Ambil nomor tertinggi di POLI yang sama (bukan cuma dokter ini)
            $maxAntrian = Reservasi::join('users', 'reservasis.dokter_id', '=', 'users.id')
                ->whereDate('reservasis.tanggal', today())
                ->where('users.poli', Auth::user()->poli)
                ->max('reservasis.nomor_antrian');

            $patient->update([
                'nomor_antrian' => $maxAntrian + 1,
                'called_at'     => null // Reset waktu panggil
            ]);
        }

        $reservasis = Reservasi::where('dokter_id', Auth::id())
            ->where('status', 'menunggu')
            ->whereDate('tanggal', today())
            ->with('pasien')
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        return view('dokter.pemeriksaan.index', compact('reservasis'));
    }

    /**
     * Memanggil pasien (Mulai timer 3 menit)
     */
    public function panggil(Reservasi $reservasi)
    {
        // 🔒 Keamanan
        if ($reservasi->dokter_id != Auth::id()) {
            abort(403);
        }

        $reservasi->update([
            'called_at' => now()
        ]);

        return back()->with('success', 'Pasien nomor ' . $reservasi->nomor_antrian . ' berhasil dipanggil. Batas tunggu 3 menit dimulai.');
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

        $obats = \App\Models\Obat::orderBy('nama_obat')->get();
        $penyakits = \App\Models\Penyakit::orderBy('nama_penyakit')->get();
        return view('dokter.pemeriksaan.create', compact('reservasi', 'obats', 'penyakits'));
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

        \DB::beginTransaction();

        try {
            $rekamMedis = RekamMedis::create([
                'reservasi_id' => $reservasi->id,
                'pasien_id'    => $reservasi->pasien_id,
                'dokter_id'    => Auth::id(),
                'tanggal'      => now(),
                'anamnesis'    => $request->anamnesis,
                'pemeriksaan'  => $request->pemeriksaan,
                'diagnosis'    => $request->diagnosis,
                'kode_icd'     => $request->kode_icd,
                'tindakan'     => $request->tindakan,
                'pengobatan'   => 'Resep Terstruktur (Lihat Detail)',
                'rujukan'      => $request->rujukan,
                'rencana_tindak_lanjut' => $request->rencana_tindak_lanjut,
                'pemeriksaan_lab' => $request->pemeriksaan_lab,
            ]);

            // SIMPAN OBAT & KURANGI STOK
            if ($request->has('obat_ids')) {
                foreach ($request->obat_ids as $index => $obatId) {
                    if (!$obatId) continue;
                    
                    $jumlah = $request->jumlahs[$index] ?? 1;
                    $obat = \App\Models\Obat::findOrFail($obatId);

                    if ($obat->stok < $jumlah) {
                        throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi (Sisa: {$obat->stok})");
                    }

                    $rekamMedis->obats()->attach($obatId, ['jumlah' => $jumlah]);
                    $obat->decrement('stok', $jumlah);
                }
            }

            // Update status reservasi
            $reservasi->update(['status' => 'selesai']);

            \DB::commit();

            return redirect()
                ->route('dokter.pemeriksaan.index')
                ->with('success', 'Pemeriksaan berhasil disimpan dan stok obat dikurangi.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage());
        }
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
