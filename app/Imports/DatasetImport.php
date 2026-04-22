<?php

namespace App\Imports;

use App\Models\User;
use App\Models\RekamMedis;
use App\Models\Reservasi;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class DatasetImport implements ToModel, WithHeadingRow, WithEvents
{
    protected $dokter = null;
    protected $import_id = null;

    public function __construct($import_id = null)
    {
        // Ambil dokter pertama yang tersedia di sistem
        $this->dokter = User::where('role', 'dokter')->first();
        $this->import_id = $import_id;
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Setiap baris file Excel dipanggil method ini.
     */
    public function model(array $row)
    {
        // Update progress di cache
        if ($this->import_id) {
            Cache::increment("import_current_{$this->import_id}");
        }

        // ─── Validasi kolom wajib ────────────────────────────────────────
        if (empty($row['id_pasien']) || empty($row['tanggal_kunjungan'])) {
            return null;
        }

        // Guard: pastikan ada dokter di sistem
        if (!$this->dokter) {
            return null;
        }

        // ─── 1. Cari atau buat pasien berdasarkan no_rm = id_pasien ─────
        $pasien = User::firstOrCreate(
            ['no_rm' => (string) $row['id_pasien']],
            [
                'name'     => $row['nama_pasien'] ?? 'Pasien ' . $row['id_pasien'],
                'email'    => 'pasien_' . $row['id_pasien'] . '@puskesmas.local',
                'password' => Hash::make('123456'),
                'role'     => 'pasien',
            ]
        );

        // Update nama jika ada perubahan di file
        if (!empty($row['nama_pasien']) && $pasien->name !== $row['nama_pasien']) {
            $pasien->update(['name' => $row['nama_pasien']]);
        }

        // ─── 2. Parse tanggal ────────────────────────────────────────────
        $tanggal = Carbon::parse($row['tanggal_kunjungan']);

        // ─── 3. Cek reservasi duplikat (pasien + tanggal sama) ──────────
        $reservasiExisting = Reservasi::where('pasien_id', $pasien->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($reservasiExisting) {
            // Sudah ada → hanya update rekam medisnya
            RekamMedis::updateOrCreate(
                ['reservasi_id' => $reservasiExisting->id],
                [
                    'pasien_id' => $pasien->id,
                    'dokter_id' => $this->dokter->id,
                    'tanggal'   => $tanggal,
                    'diagnosis' => $row['diagnosa'] ?? null,
                    'status'    => 'belum_bayar',
                ]
            );
            return null;
        }

        // ─── 4. Nomor antrian ────────────────────────────────────────────
        $lastAntrian = Reservasi::whereDate('tanggal', $tanggal)->max('nomor_antrian');

        // ─── 5. Buat Reservasi ───────────────────────────────────────────
        $reservasi = Reservasi::create([
            'pasien_id'     => $pasien->id,
            'dokter_id'     => $this->dokter->id,
            'tanggal'       => $tanggal,
            'keluhan'       => $row['diagnosa'] ?? 'Kunjungan',
            'nomor_antrian' => $lastAntrian ? $lastAntrian + 1 : 1,
            'status'        => 'selesai',
        ]);

        // ─── 6. Buat Rekam Medis ─────────────────────────────────────────
        RekamMedis::create([
            'pasien_id'    => $pasien->id,
            'dokter_id'    => $this->dokter->id,
            'reservasi_id' => $reservasi->id,
            'tanggal'      => $tanggal,
            'diagnosis'    => $row['diagnosa'] ?? null,
            'status'       => 'belum_bayar',
        ]);

        return null;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                if ($this->import_id) {
                    $totalRows = $event->getReader()->getTotalRows();
                    // Biasanya 'Worksheet' atau index 0
                    $total = array_values($totalRows)[0] ?? 0;
                    
                    // Kurangi 1 untuk header
                    if ($total > 0) $total--;

                    Cache::put("import_total_{$this->import_id}", $total, now()->addMinutes(30));
                    Cache::put("import_current_{$this->import_id}", 0, now()->addMinutes(30));
                }
            },
        ];
    }
}