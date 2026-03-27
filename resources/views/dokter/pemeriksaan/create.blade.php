@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-8 px-4">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Form Rekam Medis
    </h2>

    {{-- Informasi Pasien --}}
    <div class="bg-white shadow-md rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Informasi Pasien
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-600">Nama Pasien:</span>
                <p class="text-gray-800">{{ $reservasi->pasien->name }}</p>
            </div>

            <div>
                <span class="font-medium text-gray-600">Tanggal:</span>
                <p class="text-gray-800">
                    {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}
                </p>
            </div>

            <div class="md:col-span-2">
                <span class="font-medium text-gray-600">Keluhan:</span>
                <p class="text-gray-800">{{ $reservasi->keluhan }}</p>
            </div>
        </div>
    </div>

    {{-- Form Rekam Medis --}}
    <div class="bg-white shadow-md rounded-xl p-6">
        <form action="{{ route('dokter.pemeriksaan.store', $reservasi->id) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Anamnesis
                </label>
                <textarea name="anamnesis" rows="3"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pemeriksaan Fisik
                </label>
                <textarea name="pemeriksaan" rows="3"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Diagnosis
                    </label>
                    <input type="text" name="diagnosis"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kode ICD
                    </label>
                    <input type="text" name="kode_icd"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tindakan
                </label>
                <textarea name="tindakan" rows="2"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pengobatan
                </label>
                <textarea name="pengobatan" rows="2"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Rencana Tindak Lanjut
                </label>
                <textarea name="rencana_tindak_lanjut" rows="2"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pemeriksaan Lab
                </label>
                <textarea name="pemeriksaan_lab" rows="2"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Rujukan
                </label>
                <textarea name="rujukan" rows="2"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Simpan Rekam Medis
                </button>
            </div>

        </form>
    </div>

</div>

@endsection
