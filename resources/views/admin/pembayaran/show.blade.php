@extends('layouts.app')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Proses Pembayaran</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Invoice Layanan Medis & Obat</p>
        </div>
        <div class="text-right">
            <p class="text-[9px] font-black text-gray-400 uppercase">Status</p>
            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                Menunggu Pembayaran
            </span>
        </div>
    </div>

    {{-- INFO PASIEN --}}
    <div class="bg-gray-800 text-white rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pasien</p>
                <p class="text-xl font-bold">{{ $rekamMedis->pasien->name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Dokter Pemeriksa</p>
                <p class="text-lg font-bold">{{ $rekamMedis->dokter->name }}</p>
            </div>
        </div>
    </div>

    {{-- FORM BILLING --}}
    <form action="{{ route('admin.pembayaran.bayar', $rekamMedis->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Deskripsi Layanan / Obat</th>
                        <th class="px-6 py-4 text-center">Harga Satuan</th>
                        <th class="px-6 py-4 text-center">Jumlah (Qty)</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    {{-- BIAYA PERIKSA (FLAT) --}}
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">Biaya Pemeriksaan & Jasa Medis</p>
                            <p class="text-[10px] text-gray-400 italic">Biaya standar layanan puskesmas</p>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600 font-medium">Rp 20,000</td>
                        <td class="px-6 py-4 text-center text-gray-400">1</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">Rp 20,000</td>
                    </tr>

                    {{-- DAFTAR OBAT --}}
                    @foreach($rekamMedis->obats as $o)
                    <tr class="obat-row">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $o->nama_obat }}</p>
                            <input type="hidden" name="obat_id[]" value="{{ $o->id }}">
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600 font-medium">
                            Rp {{ number_format($o->harga) }}
                            <input type="hidden" class="harga-satuan" value="{{ $o->harga }}">
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                <input type="number" name="jumlah[]" 
                                       value="{{ $o->pivot->jumlah }}" 
                                       min="1" 
                                       class="qty-input w-20 bg-gray-50 border-gray-100 border-2 rounded-xl px-3 py-1.5 text-center text-sm focus:bg-white focus:border-green-500 transition-all outline-none font-bold">
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800 subtotal-text">
                            Rp {{ number_format($o->harga * $o->pivot->jumlah) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-800 text-white">
                    <tr>
                        <td colspan="3" class="px-6 py-5 text-right font-black uppercase tracking-widest text-[10px] text-gray-400">Total Keseluruhan</td>
                        <td class="px-6 py-5 text-right">
                            <span class="text-2xl font-black tracking-tight" id="grand-total-display">Rp 0</span>
                            <input type="hidden" name="total_bayar" value="0">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 hover:-translate-y-1 transition-all duration-300">
                Konfirmasi & Bayar Sekarang
            </button>
        </div>
    </form>
</div>

<script>
    function calculateTotal() {
        const biayaDokter = 20000;
        let totalObat = 0;

        document.querySelectorAll('.obat-row').forEach(row => {
            const harga = parseInt(row.querySelector('.harga-satuan').value);
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const subtotal = harga * qty;
            
            row.querySelector('.subtotal-text').innerText = 'Rp ' + subtotal.toLocaleString();
            totalObat += subtotal;
        });

        const grandTotal = biayaDokter + totalObat;
        document.getElementById('grand-total-display').innerText = 'Rp ' + grandTotal.toLocaleString();
        document.querySelector('input[name="total_bayar"]').value = grandTotal;
    }

    // Event Listener
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Inisialisasi awal
    calculateTotal();
</script>

@endsection