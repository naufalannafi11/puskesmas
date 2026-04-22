<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use App\Models\User;
use App\Models\RekamMedis;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::where('pasien_id', Auth::id())
            ->latest()
            ->get();

        return view('pasien.reservasi.index', compact('reservasis'));
    }

    public function create()
    {
        $polis = ['Umum', 'Gigi', 'KIA', 'KB', 'Imunisasi'];
        return view('pasien.reservasi.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'poli' => 'required',
            'dokter_id' => 'required',
            'tanggal'   => 'required|date',
            'keluhan'   => 'required'
        ]);

        // 🕒 OVERTIME PROTECTION: Cek apakah waktu dokter masih cukup (5 menit/pasien)
        $hariInggris = \Carbon\Carbon::parse($request->tanggal)->format('l');
        $hariIndo = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ][$hariInggris];

        $jadwal = \App\Models\JadwalDokter::where('dokter_id', $request->dokter_id)
            ->where('hari', $hariIndo)
            ->first();

        if ($jadwal) {
            $antreanAktif = Reservasi::where('dokter_id', $request->dokter_id)
                ->where('tanggal', $request->tanggal)
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->count();

            $estimasiMenit = ($antreanAktif + 1) * 5;
            $jamSelesai = \Carbon\Carbon::parse($request->tanggal . ' ' . $jadwal->jam_selesai);
            
            // Jika pendaftaran hari ini, hitung dari waktu sekarang
            $startTime = \Carbon\Carbon::parse($request->tanggal)->isToday() 
                ? now() 
                : \Carbon\Carbon::parse($request->tanggal . ' ' . $jadwal->jam_mulai);

            if ($startTime->addMinutes($estimasiMenit)->gt($jamSelesai)) {
                return back()->withInput()->with('error', 'Mohon maaf, kuota pelayanan Dokter hari ini sudah penuh berdasarkan estimasi jam operasional. Silakan pilih tanggal praktik berikutnya.');
            }
        }

        // Ambil data dokter untuk tahu poli-nya
        $targetDokter = User::findOrFail($request->dokter_id);

        // Ambil nomor antrian terakhir untuk POLI yang sama + tanggal tertentu
        $lastAntrian = Reservasi::join('users', 'reservasis.dokter_id', '=', 'users.id')
            ->where('reservasis.tanggal', $request->tanggal)
            ->where('users.poli', $targetDokter->poli)
            ->max('reservasis.nomor_antrian');

        $nomorAntrian = $lastAntrian ? $lastAntrian + 1 : 1;

        // Simpan reservasi
        Reservasi::create([
            'pasien_id' => Auth::id(),
            'dokter_id' => $request->dokter_id,
            'tanggal'   => $request->tanggal,
            'keluhan'   => $request->keluhan,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu'
        ]);

        return redirect()->route('pasien.reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat. Nomor antrian: ' . $nomorAntrian);
    }



    public function rekamMedis()
{
    $rekamMedis = RekamMedis::where('pasien_id', auth()->id())
        ->with('dokter')
        ->latest()
        ->get();

    return view('pasien.rekam_medis.index', compact('rekamMedis'));
}

}
