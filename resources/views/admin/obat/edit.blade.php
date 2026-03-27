@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Obat</h2>

<form method="POST" action="{{ route('admin.obat.update', $obat) }}"
      class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    @method('PUT')

    <input name="nama_obat" value="{{ $obat->nama_obat }}"
           class="w-full border p-2 rounded">

    <input name="jenis" value="{{ $obat->jenis }}"
           class="w-full border p-2 rounded">

    <input name="stok" type="number" value="{{ $obat->stok }}"
           class="w-full border p-2 rounded">

    <input name="harga" type="number" value="{{ $obat->harga }}"
           class="w-full border p-2 rounded">

    <button class="bg-green-500 text-white px-4 py-2 rounded">
        Update
    </button>
</form>

@endsection
