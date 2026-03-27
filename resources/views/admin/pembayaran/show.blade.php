@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Pembayaran Pasien</h2>

<div class="bg-white p-6 rounded shadow">

<p><b>Pasien :</b> {{ $rekamMedis->pasien->name }}</p>
<p><b>Dokter :</b> {{ $rekamMedis->dokter->name }}</p>

<form action="{{ route('admin.pembayaran.bayar',$rekamMedis->id) }}" method="POST">
@csrf

<div class="mt-4">
<label class="block mb-2">Pilih Obat</label>

@foreach($obat as $o)
<div class="flex items-center gap-2">
    <input type="checkbox"
           name="obat_id[]"
           value="{{ $o->id }}"
           data-harga="{{ $o->harga }}"
           class="obat">

    <label>
        {{ $o->nama_obat }} - Rp {{ number_format($o->harga) }}
    </label>
</div>
@endforeach

<div class="mt-4">
<label>Total Bayar</label>
<input type="number" name="total_bayar"
       class="border p-2 rounded w-full">
</div>

<button class="mt-4 bg-green-500 text-white px-4 py-2 rounded"readonly>
    Konfirmasi Bayar
</button>

</form>

</div>

<script>

let obatCheckbox = document.querySelectorAll('.obat');
let totalInput = document.querySelector('input[name="total_bayar"]');

let biayaDokter = 20000; // biaya periksa

obatCheckbox.forEach(function(cb){

    cb.addEventListener('change', function(){

        let total = biayaDokter; // mulai dari biaya dokter

        obatCheckbox.forEach(function(o){
            if(o.checked){
                total += parseInt(o.dataset.harga);
            }
        });

        totalInput.value = total;

    });

});

</script>

@endsection