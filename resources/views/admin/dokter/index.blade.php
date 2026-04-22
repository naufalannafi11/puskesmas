@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Dokter</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Daftar Tenaga Medis & Spesialisasi</p>
        </div>
        <a href="{{ route('admin.dokter.create') }}"
           class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-green-700 transition-all duration-200 active:scale-95 text-sm">
            Tambah Dokter Baru
        </a>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Alamat Email</th>
                    <th class="px-6 py-4">Poliklinik / Spesialis</th>
                    <th class="px-6 py-4 text-center">Aksi Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @foreach($dokters as $dokter)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $dokter->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $dokter->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-full font-bold text-[10px]">
                            Poli {{ $dokter->poli ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.dokter.edit', $dokter) }}"
                               class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition shadow-sm">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.dokter.destroy', $dokter) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus data dokter ini?')"
                                        class="px-4 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-bold hover:bg-red-100 transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection