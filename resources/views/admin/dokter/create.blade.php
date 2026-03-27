@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Dokter</h2>
<form method="POST" action="{{route('admin.dokter.store') }}"
class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    <input name="name" placeholder="Nama Dokter" class="w-full border p-2 rounded">
    <input name="email" type="email" placeholder="Email" class="w-full border p-2 rounded">
    <input name="password" type="password" placeholder="Password" class="w-full border p-2 rounded">

    <select name="poli" class="w-full border p-2 rounded" required>
        <option value="">-- Pilih Poli --</option>
        <option value="Umum">Poli Umum</option>
        <option value="Gigi">Poli Gigi</option>
        <option value="KIA">Poli KIA</option>
        <option value="KB">Poli KB</option>
        <option value="Imunisasi">Poli Imunisasi</option>
    </select>

    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

@endsection