@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-10 px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Detail Rekam Medis
        </h1>

        <a href="{{ route('pasien.riwayat') }}"
           class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">
            ← Kembali
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8">

        <!-- Info Utama -->
        <div class="grid md:grid-cols-2 gap-6 border-b pb-6">

            <div>
                <p class="text-sm text-gray-500">Nama Pasien</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $rekam_medis->pasien->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Pemeriksaan</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($rekam_medis->tanggal)->format('d M Y') }}
                </p>
            </div>

        </div>

        <!-- Diagnosis Section -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">

            <h2 class="text-lg font-semibold text-blue-800 mb-4">
                Diagnosis
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500">Diagnosis</p>
                    <p class="font-semibold text-gray-800">
                        {{ $rekam_medis->diagnosis ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kode ICD</p>
                    <p class="font-semibold text-gray-800">
                        {{ $rekam_medis->kode_icd ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        <!-- Detail Pemeriksaan -->
        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">Anamnesis</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->anamnesis ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pemeriksaan Fisik</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->pemeriksaan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tindakan</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->tindakan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pengobatan</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->pengobatan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Rencana Tindak Lanjut</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->rencana_tindak_lanjut ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pemeriksaan Lab</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->pemeriksaan_lab ?? '-' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Rujukan</p>
                <p class="text-gray-800 mt-1 whitespace-pre-line">
                    {{ $rekam_medis->rujukan ?? '-' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection
