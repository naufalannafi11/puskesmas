@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">
        Dashboard Pasien
    </h1>


    {{-- ================= NOMOR ANTRIAN ANDA ================= --}}
    @if ($antrianSaya)

        <div class="bg-green-50 border border-green-200 p-6 rounded shadow mb-6">

            <h2 class="text-xl font-semibold mb-3">
                Nomor Antrian Anda
            </h2>

            <p class="text-sm text-gray-600">
                Poli
            </p>

            <p class="font-semibold text-lg">
                {{ $antrianSaya->dokter->poli ?? '-' }}
            </p>

            <p class="text-sm text-gray-600 mt-3">
                Nomor Antrian
            </p>

            <p class="text-4xl font-bold text-green-600">
                {{ $antrianSaya->nomor_antrian }}
            </p>

        </div>

    @endif


    {{-- ================= INFORMASI ANTRIAN PER POLI ================= --}}
    <div class="bg-white p-6 rounded shadow mb-6">

        <h2 class="text-xl font-semibold mb-4">
            Informasi Antrian Per Poli
        </h2>

        @foreach ($antrianPoli as $poli => $antrian)

            <div class="border border-gray-200 p-4 rounded mb-3 bg-gray-50">

                <p class="text-sm text-gray-600">
                    Poli
                </p>

                <p class="font-semibold text-lg">
                    {{ $poli }}
                </p>

                @if ($antrian)

                    <p class="text-sm text-gray-600 mt-2">
                        Sedang Dilayani
                    </p>

                    <p class="text-2xl font-bold text-blue-600">
                        {{ $antrian->nomor_antrian }}
                    </p>

                @else

                    <p class="text-gray-500 mt-2">
                        Belum ada antrian
                    </p>

                @endif

            </div>

        @endforeach

    </div>


    {{-- ================= RIWAYAT RESERVASI ================= --}}
    <div class="bg-white p-6 rounded shadow">

        <h2 class="text-xl font-semibold mb-4">
            Riwayat Reservasi
        </h2>

        <table class="w-full border">

            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Dokter</th>
                    <th class="p-2 border">Poli</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Keluhan</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($reservasis as $r)

                    <tr>
                        <td class="p-2 border">
                            {{ $r->dokter->name ?? '-' }}
                        </td>

                        <td class="p-2 border">
                            {{ $r->dokter->poli ?? '-' }}
                        </td>

                        <td class="p-2 border">
                            {{ $r->tanggal }}
                        </td>

                        <td class="p-2 border">
                            {{ $r->keluhan }}
                        </td>

                        <td class="p-2 border capitalize">
                            {{ $r->status }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center p-4">
                            Belum ada reservasi
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection