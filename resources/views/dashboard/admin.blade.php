@extends('layouts.app')

@section('content')

<div class="space-y-10 py-6">
    {{-- HEADER --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Ringkasan Dashboard</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Status operasional puskesmas secara real-time</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-xs font-bold text-gray-600">Sistem Informasi Puskesmas</p>
        </div>
    </div>

    {{-- TOP STATISTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-hover hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Dokter</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalDokter ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-hover hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pasien</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalPasien ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-hover hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Rekam Medis</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalRekamMedis ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-hover hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ketersediaan Obat</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalObat ?? 0 }}</h3>
        </div>
    </div>

    {{-- BRAIN SECTION: AI PREDICTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- PREDIKSI HARI INI  --}}
        <div class="bg-gray-300 rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden transition-hover hover:shadow-md transition-all">
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-widest mb-4">Ramalan AI Hari Ini</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-5xl font-black ">{{ $prediksi ?? 0 }}</h3>
                    <span class="text-xs font-bold ">Pasien</span>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-[9px] font-black leading-relaxed uppercase tracking-widest">
                            Metode: Exponential Smoothing (EIS)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SMART INSIGHTS --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Analisis Wawasan Pintar</p>
            
            <div class="space-y-4">
                @forelse($smartInsights as $insight)
                    <div class="flex items-start gap-4 p-4 rounded-2xl border {{ $insight['type'] == 'warning' ? 'bg-red-50/50 border-red-100 text-red-600' : 'bg-blue-50/50 border-blue-100 text-blue-600' }}">
                        <div class="w-2 h-2 mt-1.5 rounded-full {{ $insight['type'] == 'warning' ? 'bg-red-400' : 'bg-blue-400' }}"></div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">{{ $insight['type'] == 'warning' ? 'Peringatan Penting' : 'Rekomendasi' }}</p>
                            <p class="text-sm font-bold leading-relaxed">{{ $insight['message'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-gray-50 rounded-2xl border border-gray-50">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Stabilitas Terjaga</p>
                        <p class="text-xs text-gray-400 font-medium">Sistem tidak menemukan anomali beban kerja hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- DATA GRIDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- PASIEN TERBARU --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pasien Terdaftar Terbaru</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-3">
                    @foreach ($latestPasien ?? [] as $p)
                        <li class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-xl transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center text-[10px] font-black">{{ substr($p->name, 0, 1) }}</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $p->name }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">No RM: {{ $p->no_rm }}</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- REKAM MEDIS TERBARU --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rekam Medis Terproses</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-3">
                    @foreach ($latestRekamMedis ?? [] as $rm)
                        <li class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-xl transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center text-[10px] font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($rm->tanggal)->format('d/m') }}</div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $rm->pasien->name }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Diagnosa: {{ $rm->diagnosis ?? '-' }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- DAILY QUEUES --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-50">
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Status Antrean Poliklinik</h3>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Monitor alur pelayanan setiap unit hari ini</p>
        </div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($antrianHariIni as $poli => $antrian)
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-50 group hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest">Poli {{ $poli }}</h4>
                        <span class="px-3 py-1 bg-white border border-gray-100 rounded-lg text-[10px] font-black text-gray-400 tracking-widest">{{ count($antrian) }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($antrian->take(3) as $item)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-blue-500 w-5">#{{ $item->nomor_antrian }}</span>
                                    <span class="text-[11px] font-bold text-gray-600 truncate max-w-[100px]">{{ $item->pasien->name }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tighter
                                    {{ $item->status == 'menunggu' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $item->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center py-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Tidak ada antrean</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection