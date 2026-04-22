@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8 space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Rekam Medis Pasien</h2>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-1">Review informasi kesehatan hasil kunjungan Anda</p>
        </div>
        <a href="{{ route('pasien.riwayat') }}" 
           class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition shadow-sm">
            Kembali ke Riwayat
        </a>
    </div>

    {{-- CARD UTAMA --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        
        {{-- SEKSI 1: IDENTITAS --}}
        <div class="p-8 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tenaga Medis (Dokter)</p>
                    <p class="text-lg font-bold text-gray-800">{{ optional($rekam_medis->dokter)->name ?? 'Dokter Pemeriksa' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5 uppercase tracking-widest">Informasi Dokter Unit Layanan</p>
                </div>
                <div class="md:text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Pemeriksaan</p>
                    <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($rekam_medis->tanggal)->format('d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- SEKSI 2: DIAGNOSA --}}
        <div class="p-8 border-b border-gray-100 bg-gray-50/30">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Diagnosa</p>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $rekam_medis->diagnosis ?? 'Dalam Observasi' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kode ICD-10</p>
                    <p class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-lg text-sm font-mono font-bold text-gray-700">
                        {{ $rekam_medis->kode_icd ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- SEKSI 3: TEMUAN KLINIS --}}
        <div class="p-8 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Anamnesis (Keluhan)</p>
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100 whitespace-pre-line">
                        {{ $rekam_medis->anamnesis ?? 'Tidak ada catatan keluhan.' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tindakan Medis</p>
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100 whitespace-pre-line">
                        {{ $rekam_medis->tindakan ?? 'Tidak ada tindakan khusus.' }}
                    </p>
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pemeriksaan Fisik</p>
                    <p class="text-sm text-gray-600 font-medium whitespace-pre-line">{{ $rekam_medis->pemeriksaan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pemeriksaan Lab</p>
                    <p class="text-sm text-gray-600 font-medium whitespace-pre-line">{{ $rekam_medis->pemeriksaan_lab ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- SEKSI 4: TERAPI & RENCANA --}}
        <div class="p-8 space-y-8">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Resep Obat & Pengobatan</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($rekam_medis->obats as $obat)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="text-sm font-bold text-gray-700">{{ $obat->nama_obat }}</span>
                            <span class="text-[10px] font-black text-gray-500 bg-white px-2 py-1 rounded border border-gray-100">{{ $obat->pivot->jumlah }} UNIT</span>
                        </div>
                    @empty
                        <p class="text-xs italic text-gray-400 col-span-2">Tidak ada resep obat terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Rencana Tindak Lanjut</p>
                    <p class="text-sm text-gray-600 font-medium">{{ $rekam_medis->rencana_tindak_lanjut ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Rujukan</p>
                    <p class="text-sm text-gray-600 font-medium">{{ $rekam_medis->rujukan ?? '-' }}</p>
                </div>
            </div>
        </div>

    </div>
    
    {{-- PRINT ACTION --}}
    <div class="flex justify-center pt-4">
        <button onclick="window.print()" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition underline underline-offset-4">
            Cetak Dokumen Rekam Medis
        </button>
    </div>
</div>

<style>
    @media print {
        aside, nav, .mb-4, .flex.justify-center.pt-4 { display: none !important; }
        main { padding: 0 !important; background: white !important; }
        .bg-white { border: none !important; box-shadow: none !important; }
        .bg-gray-50\/30 { background-color: #f9fafb !important; }
        .rounded-2xl { border-radius: 0 !important; }
    }
</style>

@endsection
