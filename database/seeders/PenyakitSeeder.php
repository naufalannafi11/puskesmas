<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penyakits = [
            ['kode_icd' => 'J00', 'nama_penyakit' => 'Nasofaringitis Akut (Common Cold / Batuk Pilek)'],
            ['kode_icd' => 'J02.9', 'nama_penyakit' => 'Faringitis Akut (Radang Tenggorokan)'],
            ['kode_icd' => 'K29.7', 'nama_penyakit' => 'Gastritis (Sakit Maag)'],
            ['kode_icd' => 'I10', 'nama_penyakit' => 'Hipertensi Esensial (Darah Tinggi)'],
            ['kode_icd' => 'E11.9', 'nama_penyakit' => 'Diabetes Melitus Tipe 2 (Kencing Manis)'],
            ['kode_icd' => 'A09.9', 'nama_penyakit' => 'Diare dan Gastroenteritis'],
            ['kode_icd' => 'R50.9', 'nama_penyakit' => 'Demam yang Tidak Spesifik'],
            ['kode_icd' => 'L23.9', 'nama_penyakit' => 'Dermatitis Kontak Alergi (Gatal-gatal)'],
            ['kode_icd' => 'J06.9', 'nama_penyakit' => 'ISPA (Infeksi Saluran Pernapasan Akut)'],
            ['kode_icd' => 'M79.1', 'nama_penyakit' => 'Mialgia (Nyeri Otot)'],
            ['kode_icd' => 'H10.9', 'nama_penyakit' => 'Konjungtivitis (Sakit Mata)'],
            ['kode_icd' => 'B35.4', 'nama_penyakit' => 'Tinea Corporis (Kurap / Jamur Kulit)'],
            ['kode_icd' => 'K02.9', 'nama_penyakit' => 'Karies Gigi (Gigi Berlubang)'],
            ['kode_icd' => 'N39.0', 'nama_penyakit' => 'Infeksi Saluran Kemih (ISK)'],
            ['kode_icd' => 'G44.2', 'nama_penyakit' => 'Tension Headache (Sakit Kepala Tegangan)'],
            ['kode_icd' => 'J45', 'nama_penyakit' => 'Asma Bronkial'],
            ['kode_icd' => 'A01.0', 'nama_penyakit' => 'Demam Tifoid (Tipes)'],
            ['kode_icd' => 'B37.0', 'nama_penyakit' => 'Kandidiasis Oral (Jamur Mulut)'],
            ['kode_icd' => 'D64.9', 'nama_penyakit' => 'Anemia'],
            ['kode_icd' => 'H66.9', 'nama_penyakit' => 'Otitis Media (Infeksi Telinga Tengah)'],
            ['kode_icd' => 'K05.6', 'nama_penyakit' => 'Penyakit Gusi (Gingivitis)'],
            ['kode_icd' => 'L01.0', 'nama_penyakit' => 'Impetigo'],
            ['kode_icd' => 'L70.9', 'nama_penyakit' => 'Akne Vulgaris (Jerawat Parah)'],
            ['kode_icd' => 'N04', 'nama_penyakit' => 'Sindrom Nefrotik'],
            ['kode_icd' => 'R05', 'nama_penyakit' => 'Batuk'],
            ['kode_icd' => 'S01', 'nama_penyakit' => 'Luka Terbuka pada Kepala'],
            ['kode_icd' => 'T14.0', 'nama_penyakit' => 'Luka Lecet / Superfisial'],
            ['kode_icd' => 'Z00.0', 'nama_penyakit' => 'Pemeriksaan Kesehatan Umum (MCU)'],
            ['kode_icd' => 'J01.9', 'nama_penyakit' => 'Sinusitis Akut'],
            ['kode_icd' => 'I20.9', 'nama_penyakit' => 'Angina Pectoris (Nyeri Dada)'],
        ];

        foreach ($penyakits as $p) {
            DB::table('penyakits')->updateOrInsert(
                ['kode_icd' => $p['kode_icd']],
                ['nama_penyakit' => $p['nama_penyakit'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
