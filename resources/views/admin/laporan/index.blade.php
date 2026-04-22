@extends('layouts.app')

@section('content')

{{-- CSS KHUSUS PRINT --}}
<style>
    @media print {
        aside, nav, button, form, .no-print {
            display: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        .print-only {
            display: block !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
        }
    }
    .print-only { display: none; }
</style>

<div class="space-y-6">
    {{-- HEADER & FILTER --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center no-print">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Laporan & Statistik</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Analisis Kunjungan, Pendapatan & Diagnosa</p>
        </div>
        
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-wrap gap-3 mt-4 md:mt-0">
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                    class="bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none font-bold">
                <span class="text-gray-300">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                    class="bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none font-bold">
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition-all">
                Filter Data
            </button>
            <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all flex items-center gap-2">
                Export / Print
            </button>
        </form>
    </div>

    {{-- KOP SURAT (Hanya muncul saat print) --}}
    <div class="print-only text-center border-b-2 border-black pb-4 mb-8">
        <h1 class="text-2xl font-bold uppercase">LAPORAN OPERASIONAL PUSKESMAS</h1>
        <p class="text-sm italic">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    </div>

    {{-- HIGHLIGHT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Kunjungan</p>
            <h3 class="text-3xl font-black text-blue-600 mt-1">{{ collect($chartKunjungan['values'])->sum() }} <small class="text-xs text-gray-400 font-bold uppercase">Pasien</small></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
            <h3 class="text-3xl font-black text-green-600 mt-1">Rp {{ number_format($totalPendapatan) }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Diagnosa Unik</p>
            <h3 class="text-3xl font-black text-rose-600 mt-1">{{ $penyakitTerbanyak->count() }} <small class="text-xs text-gray-400 font-bold uppercase">Penyakit</small></h3>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- GRAFIK KUNJUNGAN --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-6">Tren Kunjungan Pasien</h3>
            <canvas id="chartKunjungan" height="200"></canvas>
        </div>
        {{-- GRAFIK PENDAPATAN --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-6">Tren Pendapatan (Rp)</h3>
            <canvas id="chartPendapatan" height="200"></canvas>
        </div>
    </div>

    {{-- TABEL PENYAKIT TERBANYAK --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">10 Penyakit Terbanyak (Top Diseases)</h3>
            <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Berdasarkan ICD-10</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Peringkat</th>
                        <th class="px-8 py-4">Kode ICD</th>
                        <th class="px-8 py-4">Nama Penyakit</th>
                        <th class="px-8 py-4 text-center">Jumlah Kasus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($penyakitTerbanyak as $index => $p)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-8 py-4 font-black text-gray-300">#{{ $index + 1 }}</td>
                        <td class="px-8 py-4 font-mono font-bold text-rose-600">{{ $p->kode_icd }}</td>
                        <td class="px-8 py-4 font-bold text-gray-800">{{ $p->penyakit->nama_penyakit ?? 'Nama Tidak Ditemukan' }}</td>
                        <td class="px-8 py-4 text-center">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded-lg text-xs font-black">{{ $p->jumlah }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-gray-300 italic">Belum ada data diagnosa dalam periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. CHART KUNJUNGAN
    const ctxKunjungan = document.getElementById('chartKunjungan').getContext('2d');
    new Chart(ctxKunjungan, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartKunjungan['labels']) !!},
            datasets: [{
                label: 'Pasien',
                data: {!! json_encode($chartKunjungan['values']) !!},
                borderColor: '#2563eb',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(37, 99, 235, 0.05)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // 2. CHART PENDAPATAN
    const ctxPendapatan = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctxPendapatan, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartPendapatan['labels']) !!},
            datasets: [{
                label: 'Rupiah',
                data: {!! json_encode($chartPendapatan['values']) !!},
                backgroundColor: '#059669',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>

@endsection
