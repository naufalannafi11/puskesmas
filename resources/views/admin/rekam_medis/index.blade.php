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

    {{-- SEARCH --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <input type="text" name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama pasien / No RM..."
                       class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl pl-4 py-2 text-sm transition shadow-sm">
            </div>
            <button class="bg-gray-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition shadow-sm">
                Cari Arsip
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">No. Rekam Medis</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Dokter Pemeriksa</th>
                    <th class="px-6 py-4">Tanggal Periksa</th>
                    <th class="px-6 py-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rekamMedis as $rm)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded text-[10px]">
                            {{ $rm->pasien->no_rm }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $rm->pasien->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $rm->dokter->name }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $rm->tanggal }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.rekam_medis.show', $rm->id) }}"
                               class="px-4 py-1.5 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-[10px] font-bold hover:bg-gray-100 transition shadow-sm">
                                Detail
                            </a>

                            <form method="POST" action="{{ route('admin.rekam_medis.destroy', $rm) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus rekam medis ini?')"
                                        class="px-4 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-bold hover:bg-red-100 transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-400 font-bold italic tracking-wide">
                        Belum ada data rekam medis yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $rekamMedis->links() }}
    </div>
</div>

@endsection
