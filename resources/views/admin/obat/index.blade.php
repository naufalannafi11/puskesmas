@extends('layouts.app')

@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Inventaris Obat</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manajemen Stok & Harga Obat</p>
        </div>
        <a href="{{ route('admin.obat.create') }}"
           class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-green-700 transition-all duration-200 active:scale-95">
            Tambah Stok Obat
        </a>
    </div>

    {{-- SEARCH --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nama obat..."
                       class="w-full border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl pl-4 py-2 text-sm transition shadow-sm">
            </div>
            <button class="bg-gray-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition shadow-sm">
                Cari Obat
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">Nama Produk</th>
                    <th class="px-6 py-4">Kategori / Jenis</th>
                    <th class="px-6 py-4">Sisa Stok</th>
                    <th class="px-6 py-4">Harga Satuan</th>
                    <th class="px-6 py-4 text-center">Aksi Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($obats as $obat)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $obat->nama_obat }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">
                            {{ $obat->jenis }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold {{ $obat->stok < 10 ? 'text-red-500' : 'text-gray-700' }}">
                            {{ $obat->stok }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-emerald-600 font-bold">
                        Rp {{ number_format($obat->harga,0,',','.') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.obat.edit', $obat) }}"
                               class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition shadow-sm">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.obat.destroy', $obat) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus data obat ini?')"
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
                        Belum ada data obat yang ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $obats->links() }}
    </div>
</div>

@endsection
