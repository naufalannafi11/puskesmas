@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Rekam Medis</h2>

<form method="POST"
      action="{{ route('admin.rekam_medis.store') }}"
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
            <input name="kode_icd"
                   class="w-full border p-2 rounded">
        </div>
    </div>

    <div>
        <label class="font-semibold">Rencana & Tindakan (Planning)</label>
        <textarea name="tindakan"
                  class="w-full border p-2 rounded"></textarea>
    </div>

    <div>
        <label class="font-bold text-gray-700 block mb-2">Resep & Pengobatan Terstruktur</label>
        
        <div id="medicine-container" class="space-y-3">
            <div class="flex gap-3 items-center medicine-row">
                <select name="obat_ids[]" class="flex-1 border p-2.5 rounded-xl text-sm focus:ring-green-500">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($obats as $obat)
                        <option value="{{ $obat->id }}" {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }}) - Rp {{ number_format($obat->harga,0,',','.') }}
                        </option>
                    @endforeach
                </select>
                <input type="number" name="jumlahs[]" placeholder="Jml" min="1" class="w-20 border p-2.5 rounded-xl text-sm focus:ring-green-500">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 font-bold p-2">&times;</button>
            </div>
        </div>

        <button type="button" onclick="addRow()" class="mt-3 text-xs font-bold text-green-600 hover:text-green-700 flex items-center gap-1">
             + Tambah Baris Obat
        </button>
    </div>

    {{-- Script untuk Baris Obat Dinamis --}}
    <script>
        function addRow() {
            const container = document.getElementById('medicine-container');
            const row = container.querySelector('.medicine-row').cloneNode(true);
            row.querySelector('select').value = "";
            row.querySelector('input').value = "";
            container.appendChild(row);
        }

        function removeRow(btn) {
            const container = document.getElementById('medicine-container');
            if (container.children.length > 1) {
                btn.closest('.medicine-row').remove();
            } else {
                alert('Minimal harus ada satu baris obat jika ingin diresepkan.');
            }
        }
    </script>

    <div class="pt-4">
        <button class="w-full bg-green-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-green-700 transition active:scale-95">
            Simpan Rekam Medis & Kurangi Stok
        </button>
    </div>
</form>

@endsection
