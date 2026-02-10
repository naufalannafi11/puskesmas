@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Data Dokter</h2>
    <a href="{{route('admin.dokter.create')}}"
    class="bg-green-500 text-white px-4 py-2 rounded">
        Tambah Dokter
    </a>
</div>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-grey-100">
        <tr>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 ">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($dokters as $dokter)
        <tr class="border-t">
            <td class="p-3">{{ $dokter->name }}</td>
            <td class="p-3">{{ $dokter->email }}</td>
            <td class="p-3 flex gap-2 justify-center">
                <a href="{{ route('admin.dokter.edit', $dokter) }}"
                   class="bg-yellow-400 px-3 py-1 rounded text-white">
                    Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.dokter.destroy', $dokter) }}">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 px-3 py-1 rounded text-white"
                            onclick="return confirm('Hapus dokter?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

</table>

@endsection