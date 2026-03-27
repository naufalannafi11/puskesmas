@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Obat</h2>

<form method="POST" action="{{ route('admin.obat.store') }}"
      class="bg-white p-6 rounded shadow space-y-4">
    @csrf

    <input name="nama_obat" placeholder="Nama Obat"
           class="w-full border p-2 rounded">

    <input name="jenis" placeholder="Jenis"
           class="w-full border p-2 rounded">

    <input name="stok" type="number" placeholder="Stok"
           class="w-full border p-2 rounded">

    <input name="harga" type="number" placeholder="Harga"
           class="w-full border p-2 rounded">

    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

@endsection
