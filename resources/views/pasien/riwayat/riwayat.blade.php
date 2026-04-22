@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-8 space-y-8">
    {{-- HEADER --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Riwayat Berobat</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Rekam Medis Digital & Riwayat Kunjungan Anda</p>
        </div>
        <div class="px-5 py-2.5 bg-gray-50 text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest">
            Total: {{ $rekamMedis->count() }} Kunjungan
        </div>
    </div>

    {{-- TABLE CONTENT --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-50">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tanggal</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tenaga Medis</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Diagnosa Akhir</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekamMedis as $rekam_medis)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($rekam_medis->tanggal)->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-[10px] text-gray-300 font-bold uppercase tracking-tighter">Waktu Kunjungan</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center text-xs font-black uppercase">
                                    {{ substr($rekam_medis->dokter->name ?? 'D', 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-gray-700">{{ $rekam_medis->dokter->name ?? 'Dokter Umum' }}</div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-black uppercase tracking-tight">
                                {{ $rekam_medis->diagnosis ?? 'Dalam Observasi' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ route('pasien.riwayat.show', $rekam_medis->id) }}"
                                class="inline-flex items-center gap-2 px-5 py-2 bg-gray-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-sm active:scale-95 group-hover:shadow-md">
                                Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em] mb-2">Belum Ada Catatan</div>
                            <p class="text-sm text-gray-400 max-w-xs mx-auto">Anda belum memiliki riwayat kunjungan medis yang tercatat di sistem saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
