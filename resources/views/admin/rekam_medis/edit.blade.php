@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Rekam Medis</h2>

<form method="POST"
      action="{{ route('admin.rekam-medis.update', $rekamMedi) }}"
      class="bg-white p-6 rounded shadow space-y-4">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Pasien</label>
            <select name="pasien_id" class="w-full border p-2 rounded">
                @foreach($pasiens as $pasien)
                    <option value="{{ $pasien->id }}"
                        @selected($rekamMedi->pasien_id == $pasien->id)>
                        {{ $pasien->no_rm }} - {{ $pasien->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Dokter</label>
            <select name="dokter_id" class="w-full border p-2 rounded">
                @foreach($dokters as $dokter)
                    <option value="{{ $dokter->id }}"
                        @selected($rekamMedi->dokter_id == $dokter->id)>
                        {{ $dokter->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <textarea name="anamnesis"
        class="w-full border p-2 rounded">{{ $rekamMedi->anamnesis }}</textarea>

    <textarea name="pemeriksaan"
        class="w-full border p-2 rounded">{{ $rekamMedi->pemeriksaan }}</textarea>

    <textarea name="diagnosis"
        class="w-full border p-2 rounded">{{ $rekamMedi->diagnosis }}</textarea>

    <input name="icd10"
        value="{{ $rekamMedi->icd10 }}"
        class="w-full border p-2 rounded">

    <textarea name="tindakan"
        class="w-full border p-2 rounded">{{ $rekamMedi->tindakan }}</textarea>

    <textarea name="pengobatan"
        class="w-full border p-2 rounded">{{ $rekamMedi->pengobatan }}</textarea>

    <button class="bg-blue-600 text-white px-6 py-2 rounded">
        Update
    </button>

</form>

@endsection
