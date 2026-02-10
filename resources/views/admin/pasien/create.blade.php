@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Pasien</h2>

<form method="POST" action="{{ route('admin.pasien.store') }}"
      class="bg-white p-6 rounded shadow space-y-4">
    @csrf

    <input name="name" placeholder="Nama Pasien"
           class="w-full border p-2 rounded">

    <input name="email" type="email" placeholder="Email"
           class="w-full border p-2 rounded">

    <input name="password" type="password" placeholder="Password"
           class="w-full border p-2 rounded">

    <button class="bg-blue-500 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

@endsection
