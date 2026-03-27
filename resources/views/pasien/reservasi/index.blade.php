@extends('layouts.app')

@section('content')

<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Riwayat Reservasi</h2>
    <a href="{{ route('pasien.reservasi.create') }}"
    class="bg-green-600 text-white px-4 py-2 rounded">
        Buat Reservasi
    </a>
</div>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3">No Antrian</th>
            <th class="p-3">Tanggal</th>
            <th class="p-3">Dokter</th>
            <th class="p-3">Keluhan</th>
            <th class="p-3">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reservasis as $r)
        <tr class="border-t">
            <td class="p-3">{{ $r->nomor_antrian }}</td>
            <td class="p-3">{{ $r->tanggal }}</td>
            <td class="p-3">{{ $r->dokter->name }}</td>
            <td class="p-3">{{ $r->keluhan }}</td>
            <td class="p-3 capitalize">{{ $r->status }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="p-3 text-center text-gray-500">
                Belum ada reservasi
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
