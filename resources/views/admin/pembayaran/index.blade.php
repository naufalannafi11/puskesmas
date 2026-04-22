@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Pembayaran Pelayanan</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Manajemen Transaksi & Biaya Pasien</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">Informasi Pasien</th>
                    <th class="px-6 py-4">Dokter Pemeriksa</th>
                    <th class="px-6 py-4">Tanggal Transaksi</th>
                    <th class="px-6 py-4">Total Tagihan</th>
                    <th class="px-6 py-4 text-center">Status / Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data as $rm)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-700">{{ $rm->pasien->name }}</p>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">{{ $rm->pasien->no_rm }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium italic">{{ $rm->dokter->name }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $rm->tanggal }}</td>
                    <td class="px-6 py-4">
                        <span class="text-emerald-600 font-black tracking-tight">
                            Rp {{ number_format($rm->total_bayar,0,',','.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center">
                            @if($rm->status == 'belum_bayar')
                                <a href="{{ route('admin.pembayaran.show',$rm->id) }}"
                                   class="px-5 py-1.5 bg-green-50 text-green-600 border border-green-100 rounded-lg text-[10px] font-bold hover:bg-green-100 transition shadow-sm uppercase tracking-widest">
                                   Bayar Sekarang
                                </a>
                            @else
                                <span class="px-5 py-1.5 bg-gray-50 text-gray-400 border border-gray-100 rounded-lg text-[10px] font-bold uppercase tracking-widest cursor-not-allowed">
                                   Lunas Paid
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection