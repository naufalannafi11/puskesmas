@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Import Dataset Pasien</h2>

    {{-- notifikasi sukses --}}
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form action="/admin/dataset/import" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import Excel</button>
    </form>
</div>

@endsection