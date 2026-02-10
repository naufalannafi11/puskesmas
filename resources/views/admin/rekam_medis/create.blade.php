@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Rekam Medis</h2>

<form method="POST"
      action="{{ route('admin.rekam-medis.store') }}"
      class="bg-white p-6 rounded shadow space-y-4">

    @csrf

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block mb-1">Pasien</label>
            <select name="pasien_id" class="w-full border p-2 rounded">
                @foreach($pasiens as $pasien)
                    <option value="{{ $pasien->id }}">
                        {{ $pasien->no_rm }} - {{ $pasien->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Dokter</label>
            <select name="dokter_id" class="w-full border p-2 rounded">
                @foreach($dokters as $dokter)
                    <option value="{{ $dokter->id }}">
                        {{ $dokter->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Tanggal Periksa</label>
            <input type="date" name="tanggal"
                   class="w-full border p-2 rounded">
        </div>
    </div>

    <hr>

    <div>
        <label class="font-semibold">Anamnesis (Subjektif)</label>
        <textarea name="anamnesis"
                  class="w-full border p-2 rounded"></textarea>
    </div>

    <div>
        <label class="font-semibold">Pemeriksaan Fisik (Objektif)</label>
        <textarea name="pemeriksaan"
                  class="w-full border p-2 rounded"></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="font-semibold">Diagnosis (Assessment)</label>
            <textarea name="diagnosis"
                      class="w-full border p-2 rounded"></textarea>
        </div>

        <div>
            <label class="font-semibold">Kode ICD-10</label>
            <input name="icd10"
                   class="w-full border p-2 rounded">
        </div>
    </div>

    <div>
        <label class="font-semibold">Rencana & Tindakan (Planning)</label>
        <textarea name="tindakan"
                  class="w-full border p-2 rounded"></textarea>
    </div>

    <div>
        <label class="font-semibold">Pengobatan</label>
        <textarea name="pengobatan"
                  class="w-full border p-2 rounded"></textarea>
    </div>

    <button class="bg-blue-600 text-white px-6 py-2 rounded">
        Simpan Rekam Medis
    </button>

</form>

@endsection
