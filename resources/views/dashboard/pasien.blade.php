@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">
        Dashboard Pasien
    </h1>


    {{-- ================= NOMOR ANTRIAN ANDA ================= --}}
    @if ($antrianSaya)

        <div class="bg-blue-50 border border-blue-200 p-8 rounded-2xl shadow-sm mb-8 relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight mb-4">Nomor Antrean Anda</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Poliklinik Tujuan</p>
                            <p class="font-bold text-gray-700 text-lg">
                                Poliklinik {{ $antrianSaya->poli ?? ($antrianSaya->dokter->poli ?? '-') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Antrean</p>
                            <p class="text-6xl font-black text-blue-600 tracking-tighter">
                                {{ $antrianSaya->nomor_antrian_format }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="hidden md:block">
                    <div class="w-32 h-32 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="bg-gray-50 border border-gray-100 p-8 rounded-2xl text-center mb-8">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum Ada Antrean Aktif</p>
            <p class="text-gray-300 text-sm mt-1">Silakan lakukan reservasi untuk mendapatkan nomor antrean.</p>
        </div>
    @endif


    {{-- ================= INFORMASI ANTRIAN PER POLI ================= --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">

        <h2 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-6 flex items-center gap-2">
            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
            Status Antrean Per Poli
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($antrianPoli as $poli => $antrian)
                <div class="border border-gray-50 p-6 rounded-2xl bg-gray-50/50 hover:border-blue-100 transition duration-300">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Poliklinik</p>
                    <p class="font-bold text-gray-800 mb-4">
                        {{ $poli }}
                    </p>

                    @if ($antrian)
                        <div class="bg-white p-4 rounded-xl shadow-sm inline-block">
                            <p class="text-[8px] font-black text-blue-400 uppercase tracking-widest mb-1">Sedang Dilayani</p>
                            <p class="text-2xl font-black text-blue-600">
                                {{ $antrian->nomor_antrian_format }}
                            </p>
                        </div>
                    @else
                        <span class="text-[10px] font-bold text-gray-300 uppercase italic">Belum Ada Antrean</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>


    {{-- ================= RIWAYAT RESERVASI ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-black text-gray-800 uppercase tracking-tight">Riwayat Reservasi</h2>
            <a href="{{ route('pasien.reservasi.create') }}" class="px-4 py-2 bg-blue-600 text-white text-[10px] font-black uppercase rounded-lg hover:bg-blue-700 transition">
                Daftar Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Dokter / Poli</th>
                        <th class="px-8 py-4">Antrean</th>
                        <th class="px-8 py-4">Tanggal</th>
                        <th class="px-8 py-4">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse ($reservasis as $r)
                        <tr class="hover:bg-gray-50/50 transition duration-150">
                            <td class="px-8 py-4">
                                <p class="font-bold text-gray-800">{{ $r->dokter->name ?? '-' }}</p>
                                <p class="text-[10px] font-bold text-gray-400">Poliklinik {{ $r->poli ?? ($r->dokter->poli ?? '-') }}</p>
                            </td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs font-mono font-black text-gray-600">
                                    {{ $r->nomor_antrian_format }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm text-gray-500 font-medium">
                                {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-4">
                                @if($r->status == 'selesai')
                                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase">Selesai</span>
                                @elseif($r->status == 'menunggu' || $r->status == 'panggil')
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase font-pulse">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-50 text-gray-500 rounded-full text-[9px] font-black uppercase">{{ $r->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-300 italic">Belum ada riwayat reservasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection