@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Buat Reservasi</h2>

<form method="POST" action="{{ route('pasien.reservasi.store') }}"
      class="bg-white p-6 rounded shadow space-y-4">
    @csrf

    <!-- PILIH POLI -->
    <div>
        <label class="block mb-1">Pilih Poli</label>
        <select name="poli" id="poliSelect"
                class="w-full border p-2 rounded" required>
            <option value="">-- Pilih Poli --</option>
            @foreach($polis as $poli)
                <option value="{{ $poli }}">{{ $poli }}</option>
            @endforeach
        </select>
    </div>

    <!-- PILIH DOKTER -->
    <div>
        <label class="block mb-1">Pilih Dokter</label>
        <select name="dokter_id" id="dokterSelect"
                class="w-full border p-2 rounded" required>
            <option value="">-- Pilih Dokter --</option>
        </select>
    </div>

    <input type="date" name="tanggal"
           class="w-full border p-2 rounded" required>

    <textarea name="keluhan"
              class="w-full border p-2 rounded"
              placeholder="Keluhan" required></textarea>

    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

<script>
document.getElementById('poliSelect').addEventListener('change', function() {
    let poli = this.value;
    let dokterSelect = document.getElementById('dokterSelect');

    dokterSelect.innerHTML = '<option value="">Loading...</option>';

    fetch('/get-dokter-by-poli/' + poli)
        .then(response => response.json())
        .then(data => {
            dokterSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
            data.forEach(function(dokter) {
                dokterSelect.innerHTML += 
                    `<option value="${dokter.id}">${dokter.name}</option>`;
            });
        });
});
</script>

@endsection
