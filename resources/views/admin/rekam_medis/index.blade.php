@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Data Rekam Medis</h2>

    <a href="{{ route('admin.rekam_medis.create') }}"
       class="bg-green-600 text-white px-4 py-2 rounded">
        + Tambah Rekam Medis
    </a>
</div>

<form method="GET" class="mb-4">
    <input type="text" name="search"
           value="{{ $search }}"
           placeholder="Cari nama pasien / No RM"
           class="border rounded px-3 py-2 w-64">
    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Cari
    </button>
</form>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">No RM</th>
            <th class="p-3 text-left">Pasien</th>
            <th class="p-3 text-left">Dokter</th>
            <th class="p-3 text-left">Tanggal</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekamMedis as $rm)
        <tr class="border-t">
            <td class="p-3">{{ $rm->pasien->no_rm }}</td>
            <td class="p-3">{{ $rm->pasien->name }}</td>
            <td class="p-3">{{ $rm->dokter->name }}</td>
            <td class="p-3">{{ $rm->tanggal }}</td>
            <td class="p-3 flex gap-2 justify-center">
                <a href="{{ route('admin.rekam_medis.edit', $rm) }}"
                   class="bg-yellow-400 px-3 py-1 rounded text-white">
                    Edit
                </a>

                <a href="{{ route('admin.rekam_medis.show', $rm->id) }}"
       class="bg-blue-500 px-3 py-1 rounded text-white">
        Detail
    </a>

                <form method="POST"
                      action="{{ route('admin.rekam_medis.destroy', $rm) }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus rekam medis?')"
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
    {{ $rekamMedis->links() }}
</div>

@endsection
