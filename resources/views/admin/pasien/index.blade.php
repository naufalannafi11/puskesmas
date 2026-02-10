@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Data Pasien</h2>
    <a href="{{ route('admin.pasien.create') }}"
       class="bg-green-500 text-white px-4 py-2 rounded">
        Tambah Pasien
    </a>
</div>

<form method="GET" class="mb-4">
    <input type="text"
           name="search"
           value="{{ $search }}"
           placeholder="Cari nama pasien..."
           class="border p-2 rounded w-1/3">
    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Cari
    </button>
</form>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3">No RM</th>
            <th class="p-3 text-center">Aksi</th>

        </tr>
    </thead>
    <tbody>
        @foreach($pasiens as $pasien)
        <tr class="border-t">
            <td class="p-3">{{ $pasien->name }}</td>
            <td class="p-3">{{ $pasien->email }}</td>
            <td class="p-3">{{ $pasien->no_rm }}</td>

            <td class="p-3 flex gap-2 justify-center">
                <a href="{{ route('admin.pasien.edit', $pasien) }}"
                   class="bg-yellow-400 px-3 py-1 rounded text-white">
                    Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.pasien.destroy', $pasien) }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus pasien?')"
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
    {{ $pasiens->links() }}
</div>

@endsection
