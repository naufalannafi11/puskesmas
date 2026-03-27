@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Rekam Medis
        </h1>
    </div>

    <div class="grid md:grid-cols-2 gap-6">

        @forelse($rekamMedis as $item)
        <div class="bg-white shadow-lg rounded-2xl p-6 border hover:shadow-xl transition">

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $item->pasien->name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </p>
                </div>

                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $item->diagnosis ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $item->diagnosis ?? 'Belum Ada Diagnosis' }}
                </span>
            </div>

            <div class="mb-4 text-sm text-gray-600 line-clamp-2">
                {{ $item->anamnesis }}
            </div>

            <a href="{{ route('dokter.pemeriksaan.show', $item->id) }}"
               class="inline-block w-full text-center py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                Lihat Detail
            </a>

        </div>
        @empty
        <div class="col-span-2 text-center text-gray-500">
            Belum ada data rekam medis.
        </div>
        @endforelse

    </div>

</div>

@endsection
