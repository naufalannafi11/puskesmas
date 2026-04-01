@extends('layouts.app')

@section('content')

<h2>Prediksi Pasien</h2>

<form action="{{ route('admin.prediksi.proses') }}" method="POST">
    @csrf

    <label>Hari</label>
    <select name="hari">
        <option value="1">Senin</option>
        <option value="2">Selasa</option>
        <option value="3">Rabu</option>
        <option value="4">Kamis</option>
        <option value="5">Jumat</option>
        <option value="6">Sabtu</option>
        <option value="7">Minggu</option>
    </select>

    <label>Bulan</label>
    <select name="bulan">
        @for($i=1; $i<=12; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>

    <button type="submit">Prediksi</button>
</form>

@if(session('prediksi'))
    <h3>Prediksi Pasien: {{ session('prediksi') }} orang</h3>
@endif

@endsection