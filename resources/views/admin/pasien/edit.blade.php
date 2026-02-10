@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Pasien</h2>
<form method="POST" action="{{route('admin.pasien.update', $pasien)}}"
class="bg-white p-6 rounded shadow space-y-4">
    @csrf @method('PUT')
    <input name="name" value="{{$pasien->name}}" class="w-full border p-2 rounded">
    <input name="email" value="{{$pasien->email}}" class="w-full border p-2 rounded">
    <button class="bg-yellow-500 text-white px-4 py-2 rounded">
        Update
    </button>
</form>

@endsection