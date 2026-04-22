@extends('layouts.app')

@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Arsip Rekam Medis</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Dokumentasi Riwayat Kesehatan Pasien (Read-Only)</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">No. Rekam Medis</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Diagnosis Utama</th>
                    <th class="px-6 py-4">Tanggal Periksa</th>
                    <th class="px-6 py-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rekamMedis as $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded text-[10px]">
                            {{ $item->pasien->no_rm ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $item->pasien->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded
                            {{ $item->diagnosis ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-50 text-gray-400 border border-gray-200' }}">
                            {{ $item->diagnosis ?? 'Menunggu...' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
                               class="px-5 py-2 bg-gray-800 text-white border border-gray-800 rounded-xl text-[10px] uppercase font-black tracking-widest hover:bg-blue-600 hover:border-blue-600 transition-all shadow-sm">
                                Detail Medis
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-400 font-bold tracking-widest uppercase text-xs">
                        Belum ada data arsip rekam medis pasien di bawah pengawasan Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
