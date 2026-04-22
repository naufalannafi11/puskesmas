@extends('layouts.app')

@section('content')

<div class="space-y-10 py-6">
    {{-- HEADER --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Area Kerja Dokter</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Layanan pemeriksaan dan monitor antrean pasien</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-xs font-bold text-gray-600">Poli {{ Auth::user()->poli }}</p>
        </div>
    </div>

    {{-- TOP STATISTICS (SHARED STYLE) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Menunggu</p>
            <h3 class="text-3xl font-black text-amber-500 text-center">{{ $stats['menunggu'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Sedang Diperiksa</p>
            <h3 class="text-3xl font-black text-blue-500 text-center">{{ $stats['diperiksa'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Telah Selesai</p>
            <h3 class="text-3xl font-black text-green-500 text-center">{{ $stats['selesai'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 text-center">Total Pemeriksaan</p>
            <h3 class="text-3xl font-black text-gray-800 text-center">{{ $totalRekamMedis ?? 0 }}</h3>
        </div>
    </div>

    {{-- BRAIN SECTION: AI PREDICTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- PREDIKSI KEPADATAN (ABU-ABU) --}}
        <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="relative z-10 w-full">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 text-center lg:text-left">Ramalan Beban Pasien Hari Ini</p>
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-6xl font-black text-gray-800 tracking-tighter">{{ $prediksi ?? 0 }}</h3>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Jiwa</span>
                    </div>
                    <div class="bg-white px-6 py-4 rounded-2xl border border-gray-200">
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Status Kapasitas</p>
                        <p class="text-xs font-bold text-gray-500">{{ ($prediksi ?? 0) > 23 ? 'Kapasitas Tinggi' : 'Kapasitas Normal' }}</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-200/50">
                    <p class="text-[9px] font-black text-gray-400 leading-relaxed uppercase tracking-[0.2em] text-center lg:text-left">
                        Metode Analisis: AI Exponential Smoothing (EIS)
                    </p>
                </div>
            </div>
            {{-- Decorative text removed for ultra-subdued look --}}
        </div>

        {{-- TOP DIAGNOSES (Insight Dokter) --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Tren Penyakit Terbanyak</p>
            <div class="space-y-4">
                @forelse($topDiagnoses ?? [] as $index => $diag)
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-blue-200 transition-all">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-black text-gray-300">0{{ $index + 1 }}</span>
                            <span class="text-sm font-bold text-gray-700">{{ $diag->diagnosis }}</span>
                        </div>
                        <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-widest">{{ $diag->total }} Kasus</span>
                    </div>
                @empty
                    <p class="text-center py-8 text-xs text-gray-400 italic">Data tren penyakit belum tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- DAILY QUEUES --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-gray-800 tracking-tight">Antrean Anda Hari Ini</h3>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Daftar pasien yang menunggu pemeriksaan</p>
            </div>
            <a href="{{ route('dokter.pemeriksaan.riwayat') }}" class="px-5 py-2 bg-gray-50 text-[9px] font-black uppercase tracking-widest text-gray-400 rounded-xl border border-gray-100 hover:text-blue-500 hover:border-blue-100 transition-all">Riwayat Selesai</a>
        </div>
        
        <div class="p-0 overflow-x-auto">
            @foreach ($antrianHariIni as $poli => $antrian)
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-4">No</th>
                            <th class="px-8 py-4">Nama Pasien</th>
                            <th class="px-8 py-4">Waktu Reservasi</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($antrian as $item)
                            <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-8 py-5 font-black text-blue-500">#{{ $item->nomor_antrian }}</td>
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-gray-800">{{ $item->pasien->name }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">RM: {{ $item->pasien->no_rm }}</p>
                                </td>
                                <td class="px-8 py-5 text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        {{ $item->status == 'menunggu' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('dokter.pemeriksaan.create', ['reservasi' => $item->id]) }}" 
                                       class="inline-block px-4 py-2 bg-gray-800 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 active:scale-95 transition-all">
                                        Periksa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Antrean Belum Tersedia</p>
                                    <p class="text-xs text-gray-400 font-medium">Belum ada pasien yang melakukan reservasi hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>

@endsection
