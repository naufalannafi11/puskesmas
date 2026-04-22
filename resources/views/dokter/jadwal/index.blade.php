@extends('layouts.app')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Jadwal Praktik Saya</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Waktu Pelayanan Anda di Puskesmas</p>
        </div>
        <div class="text-right">
            <p class="text-[9px] font-black text-gray-400 uppercase">Unit Tugas</p>
            <p class="text-sm font-bold text-gray-700">Poliklinik {{ Auth::user()->poli ?? '-' }}</p>
        </div>
    </div>

    {{-- INFO BOX (LIGHT) --}}
    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6">
        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Catatan Penting</p>
        <p class="text-sm text-blue-700 leading-relaxed">
            Jadwal ini dikelola oleh bagian Administrasi. Silakan hubungi bagian Tata Usaha jika terdapat ketidaksesuaian waktu atau membutuhkan perubahan shift praktik Anda.
        </p>
    </div>

    {{-- KARTU JADWAL --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($jadwals as $j)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center font-black">
                    {{ substr($j->hari, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">{{ $j->hari }}</h4>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Hari Praktik</p>
                </div>
            </div>
            <div class="text-right">
                <div class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-black font-mono">
                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                </div>
                <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Jam Operasional</p>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum Ada Jadwal</p>
            <p class="text-gray-300 text-sm mt-1">Silakan koordinasi dengan Admin untuk mendaftarkan jadwal praktik Anda.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
