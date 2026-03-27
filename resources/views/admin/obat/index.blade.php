@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Data Obat</h2>
    <a href="{{ route('admin.obat.create') }}"
       class="bg-green-500 text-white px-4 py-2 rounded">
        Tambah Obat
    </a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Cari nama obat..."
           class="border px-3 py-2 rounded w-64">
    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Cari
    </button>
</form>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Jenis</th>
            <th class="p-3 text-left">Stok</th>
            <th class="p-3 text-left">Harga</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($obats as $obat)
        <tr class="border-t">
            <td class="p-3">{{ $obat->nama_obat }}</td>
            <td class="p-3">{{ $obat->jenis }}</td>
            <td class="p-3">{{ $obat->stok }}</td>
            <td class="p-3">Rp {{ number_format($obat->harga,0,',','.') }}</td>
            <td class="p-3 flex gap-2 justify-center">
                <a href="{{ route('admin.obat.edit', $obat) }}"
                   class="bg-yellow-400 px-3 py-1 rounded text-white">
                    Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.obat.destroy', $obat) }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus obat?')"
                            class="bg-red-500 px-3 py-1 rounded text-white">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $obats->links() }}
</div>

@endsection
