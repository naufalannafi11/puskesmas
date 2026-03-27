@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Pembayaran Pasien</h1>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3">Pasien</th>
            <th class="p-3">Dokter</th>
            <th class="p-3">Tanggal</th>
            <th class="p-3">Total Bayar</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $rm)
        <tr class="border-t">
            <td class="p-3">{{ $rm->pasien->name }}</td>
            <td class="p-3">{{ $rm->dokter->name }}</td>
            <td class="p-3">{{ $rm->tanggal }}</td>
            <td class="p-3">
                Rp {{ number_format($rm->total_bayar,0,',','.') }}
            </td>
            <td class="p-3 text-center">

@if($rm->status == 'belum_bayar')

<a href="{{ route('admin.pembayaran.show',$rm->id) }}"
   class="bg-green-500 text-white px-3 py-1 rounded">
   Bayar
</a>

@else

<span class="bg-gray-400 text-white px-3 py-1 rounded">
   Sudah Bayar
</span>

@endif

</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection