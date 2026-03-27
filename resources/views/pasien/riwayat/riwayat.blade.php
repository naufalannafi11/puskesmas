@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6">Riwayat Berobat</h1>

    <table class="min-w-full bg-white shadow rounded-xl">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Dokter</th>
                <th class="px-6 py-3 text-left">Diagnosis</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekamMedis as $rekam_medis)
            <tr class="border-t">
                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($rekam_medis->tanggal)->format('d M Y') }}</td>
                <td class="px-6 py-4">{{ $rekam_medis->dokter->name ?? '-' }}</td>
                <td class="px-6 py-4">{{ $rekam_medis->diagnosis ?? '-' }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('pasien.riwayat.show', $rekam_medis->id) }}"
                       class="text-blue-600 hover:underline">
                       Lihat
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
