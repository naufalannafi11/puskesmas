@extends('layouts.app')

@section('content')

<div class="space-y-8 max-w-6xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">
                @if($selectedPoli)
                    Poliklinik {{ $selectedPoli }}
                @else
                    Pilih Poliklinik
                @endif
            </h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">
                @if($selectedPoli)
                    Daftar Dokter dan Waktu Praktik Tersedia
                @else
                    Silakan pilih layanan poliklinik untuk melihat jadwal dokter
                @endif
            </p>
        </div>

        @if($selectedPoli)
            <a href="{{ route('pasien.jadwal.index') }}" 
               class="mt-4 md:mt-0 flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali Pilih Poli
            </a>
        @endif
    </div>

    @if(!$selectedPoli)
        {{-- TAMPILAN PILIH POLI (STEP 1) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $colors = [
                    'Umum' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100'],
                    'Gigi' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100'],
                    'KIA' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
                    'KB' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
                    'Imunisasi' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100'],
                ];
            @endphp

            @foreach($polis as $p)
                <a href="{{ route('pasien.jadwal.index', ['poli' => $p]) }}" 
                   class="group relative bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                    
                    <h3 class="text-xl font-black text-gray-800 mb-2">Poliklinik {{ $p }}</h3>
                    <p class="text-sm text-gray-400 font-medium leading-relaxed mb-6">Daftar dokter dan jam praktik unit {{ strtolower($p) }}.</p>
                    
                    <div class="flex items-center gap-2 text-blue-600 text-[10px] font-black uppercase tracking-widest">
                        Buka Jadwal
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        {{-- TAMPILAN RINCIAN JADWAL (STEP 2) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwals as $j)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="p-8">
                        {{-- INFO DOKTER --}}
                        <div class="flex items-start gap-4 mb-8">
                            <div class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center font-black text-2xl border border-gray-100 uppercase">
                                {{ substr($j->dokter->name ?? 'D', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $j->dokter->name ?? '-' }}</h4>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $j->hari }}</p>
                            </div>
                        </div>

                        {{-- INFO WAKTU --}}
                        <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Waktu Praktik</span>
                            <span class="px-4 py-1.5 bg-gray-800 text-white rounded-xl text-xs font-black font-mono">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    </div>

                    {{-- FOOTER CARD --}}
                    <div class="px-8 py-5 bg-gray-50/30 border-t border-gray-50 flex justify-center items-center">
                        <a href="{{ route('pasien.reservasi.create', ['poli' => $j->poli, 'dokter_id' => $j->dokter_id]) }}" 
                           class="w-full text-center py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all active:scale-95">
                           Daftar Reservasi Sekarang
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-20 rounded-3xl shadow-sm border border-gray-100 text-center">
                    <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 text-sm font-black uppercase">
                    </div>
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2 text-center">Jadwal Belum Tersedia</h3>
                    <p class="text-gray-400 text-sm max-w-xs mx-auto text-center">Maaf, saat ini belum ada jadwal dokter yang terdaftar untuk unit poliklinik ini.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>

@endsection
