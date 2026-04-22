@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Analisis Prediksi AI (EIS)</h2>
            <p class="text-gray-500 mt-1">Gunakan kecerdasan buatan untuk meramal beban pasien & pola kunjungan.</p>
        </div>
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('admin.prediksi.proses') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pilih Tanggal Mulai</label>
                    <input type="date" name="start_date" 
                           class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" 
                           value="{{ old('start_date', date('Y-m-d')) }}">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-md hover:bg-blue-700 transition font-bold shadow-md mt-4">
                    Analisis Tren
                </button>
            </form>
        </div>
    </div>

    @if(session('hasil'))
        @php $hasil = session('hasil'); @endphp

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white">
                <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Rata-rata Pasien / Hari</p>
                <h3 class="text-4xl font-black mt-2">{{ $hasil['summary']['avg_demand'] }} <span class="text-lg font-normal text-blue-100">Orang</span></h3>
                <p class="mt-4 text-sm text-blue-50/80 italic">Berdasarkan pola Exponential Smoothing (EIS).</p>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl shadow-lg text-white">
                <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider">Interval Kunjungan</p>
                <h3 class="text-4xl font-black mt-2">{{ $hasil['summary']['avg_interval'] }} <span class="text-lg font-normal text-emerald-100">Hari</span></h3>
                <p class="mt-4 text-sm text-emerald-50/80 italic">Estimasi jarak antar kunjungan pasien.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex flex-col justify-center">
                <h4 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                    AI Insights
                </h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    @foreach($hasil['summary']['insights'] as $insight)
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 mt-1">•</span>
                            <span>{{ $insight }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- CHART & TABLE --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            {{-- GRAFIK BEBAN PASIEN --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    Tren Beban Pasien (7 Hari)
                </h4>
                <div style="height: 300px;">
                    <canvas id="demandChart"></canvas>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    Rincian Harian Prediksi
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Tanggal / Hari</th>
                                <th class="px-4 py-3 text-center">Estimasi Pasien</th>
                                <th class="px-4 py-3 text-center">Interval (Hari)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($hasil['data']['demand'] as $index => $item)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($item['tanggal'])->isoFormat('DD MMM YYYY') }}</div>
                                        <div class="text-xs text-gray-400 font-medium">{{ $item['hari'] }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-bold">
                                            {{ round($item['nilai']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-600 font-medium">
                                        {{ $hasil['data']['interval'][$index]['nilai'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- SCRIPT CHART.JS --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('demandChart').getContext('2d');
            const data = @json($hasil['data']['demand']);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(i => i.hari),
                    datasets: [{
                        label: 'Prediksi Jumlah Pasien',
                        data: data.map(i => i.nilai),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        </script>

    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white p-16 rounded-2xl shadow-lg border border-gray-100 text-center">
            <h3 class="text-2xl font-bold text-gray-800">Modul Prediksi Siap Beroperasi</h3>
            <p class="text-gray-500 max-w-md mx-auto mt-2">Silakan pilih tanggal mulai di atas untuk memantau ramalan beban pasien menggunakan metode <strong>Exponential Smoothing (EIS)</strong>.</p>
        </div>
    @endif

</div>
@endsection