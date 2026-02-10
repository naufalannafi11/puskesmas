@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Dokter</p>
        <h2 class="text-3xl font-bold">{{ $totalDokter ?? 0}}</h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Pasien</p>
        <h2 class="text-3xl font-bold">{{ $totalPasien ?? 0}}</h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Rekam Medis</p>
        <h2 class="text-3xl font-bold">{{ $totalRekamMedis ?? 0}}</h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Obat</p>
        <h2 class="text-3xl font-bold">{{ $totalObat ?? 0}}</h2>
    </div>
</div>


{{-- DATA TERBARU --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold mb-3">Pasien Terbaru</h3>
        <ul class="space-y-2">
            @foreach($latestPasien ?? [] as $p)
                <li class="border-b pb-1">
                    {{ $p->name }} <span class="text-gray-500">({{ $p->no_rm }})</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold mb-3">Rekam Medis Terbaru</h3>
        <ul class="space-y-2">
            @foreach($latestRekamMedis ?? [] as $rm)
                <li class="border-b pb-1">
                    {{ $rm->pasien->name }} – {{ $rm->tanggal }}
                </li>
            @endforeach
        </ul>
    </div>

</div>

@endsection
