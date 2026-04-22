@extends('layouts.app')

@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Data Pasien</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manajemen Basis Data Pasien</p>
        </div>
        <a href="{{ route('admin.pasien.create') }}"
           class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-green-700 transition-all duration-200 active:scale-95">
            Tambah Pasien Baru
        </a>
    </div>

    {{-- SEARCH --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau No RM..."
                       class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl pl-4 py-2 text-sm transition shadow-sm">
            </div>
            <button class="bg-gray-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition shadow-sm">
                Cari Data
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">Informasi Rekam Medis</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Kontak Email</th>
                    <th class="px-6 py-4 text-center">Aksi Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pasiens as $pasien)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 bg-green-50 text-green-700 rounded-full font-bold text-[10px]">
                            {{ $pasien->no_rm }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $pasien->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $pasien->email }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.pasien.edit', $pasien) }}"
                               class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition shadow-sm">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.pasien.destroy', $pasien) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus data pasien ini?')"
                                        class="px-4 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-bold hover:bg-red-100 transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-gray-400 font-bold italic tracking-wide">
                        Belum ada data pasien yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pasiens->links() }}
    </div>
</div>

@endsection
