@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Dashboard Admin
</h1>

{{-- ================= STATISTIK + AI ================= --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4">

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Dokter</p>
        <h2 class="text-3xl font-bold">
            {{ $totalDokter ?? 0 }}
        </h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Pasien</p>
        <h2 class="text-3xl font-bold">
            {{ $totalPasien ?? 0 }}
        </h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Rekam Medis</p>
        <h2 class="text-3xl font-bold">
            {{ $totalRekamMedis ?? 0 }}
        </h2>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p class="text-gray-500">Obat</p>
        <h2 class="text-3xl font-bold">
            {{ $totalObat ?? 0 }}
        </h2>
    </div>

    {{-- 🔥 AI PREDIKSI --}}
    <div class="bg-blue-500 text-white p-4 rounded shadow">
        <p class="text-sm">Prediksi Hari Ini</p>
        <h2 class="text-3xl font-bold">
            {{ $prediksi ?? 0 }} orang
        </h2>
    </div>

</div>


{{-- ================= DATA TERBARU ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">

    {{-- PASIEN TERBARU --}}
    <div class="bg-white p-4 rounded shadow">

        <h3 class="font-bold mb-3">
            Pasien Terbaru
        </h3>

        <ul class="space-y-2">

            @foreach ($latestPasien ?? [] as $p)
                <li class="border-b pb-1">
                    {{ $p->name }}
                    <span class="text-gray-500">
                        ({{ $p->no_rm }})
                    </span>
                </li>
            @endforeach

        </ul>

    </div>


    {{-- REKAM MEDIS TERBARU --}}
    <div class="bg-white p-4 rounded shadow">

        <h3 class="font-bold mb-3">
            Rekam Medis Terbaru
        </h3>

        <ul class="space-y-2">

            @foreach ($latestRekamMedis ?? [] as $rm)
                <li class="border-b pb-1">
                    {{ $rm->pasien->name }} – {{ $rm->tanggal }}
                </li>
            @endforeach

        </ul>

    </div>

</div>


{{-- ================= ANTRIAN HARI INI ================= --}}
<div class="bg-white p-6 rounded shadow mt-10">

    <h3 class="font-bold mb-4">
        Antrian Hari Ini
    </h3>

    @foreach ($antrianHariIni as $poli => $antrian)

        <div class="mb-8">

            <h4 class="font-semibold text-blue-600 mb-3">
                Poli {{ $poli }}
            </h4>

            <table class="min-w-full border rounded">

                <thead>
                    <tr class="bg-gray-100 text-sm">
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Pasien</th>
                        <th class="px-4 py-2 border">Dokter</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($antrian as $item)

                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2 border">
                                {{ $item->nomor_antrian }}
                            </td>

                            <td class="px-4 py-2 border">
                                {{ $item->pasien->name }}
                            </td>

                            <td class="px-4 py-2 border">
                                {{ $item->dokter->name }}
                            </td>

                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $item->status == 'menunggu' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">
                                Belum ada antrian
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    @endforeach

</div>

@endsection