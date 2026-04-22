@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Antrean Pasien Hari Ini</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Kelola pemanggilan dan pemeriksaan pasien</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Tanggal</p>
            <p class="text-sm font-bold text-gray-700">{{ date('d M Y') }}</p>
        </div>
    </div>

    @if($reservasis->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-6 py-4">No. Antrean</th>
                        <th class="px-6 py-4">Data Pasien</th>
                        <th class="px-6 py-4">Status & Waktu</th>
                        <th class="px-6 py-4 text-center">Tindakan Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($reservasis as $r)
                    <tr class="hover:bg-gray-50/50 transition @if($r->called_at) bg-amber-50/30 @endif">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded bg-gray-800 text-white font-black text-xs px-2">
                                {{ $r->nomor_antrian_format }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $r->pasien->name }}</p>
                            <p class="text-xs text-gray-500 italic">{{ Str::limit($r->keluhan, 40) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($r->called_at)
                                <div class="space-y-1">
                                    <span class="px-2 py-1 bg-amber-100 text-amber-600 rounded text-[10px] font-black uppercase tracking-tighter animate-pulse">
                                        Sedang Dipanggil
                                    </span>
                                    <div class="text-[11px] font-mono font-bold text-rose-500 timer-display" 
                                         data-start-ms="{{ \Carbon\Carbon::parse($r->called_at)->timestamp * 1000 }}">
                                        Batas Waktu: 00:00
                                    </div>
                                </div>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-[10px] font-black uppercase tracking-tighter">
                                    Menunggu
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 justify-center">
                                {{-- TOMBOL PANGGIL --}}
                                @if(!$r->called_at)
                                <form action="{{ route('dokter.pemeriksaan.panggil', $r->id) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl text-xs font-bold hover:bg-amber-100 transition shadow-sm">
                                        Panggil Pasien
                                    </button>
                                </form>
                                @endif

                                {{-- TOMBOL PERIKSA --}}
                                <a href="{{ route('dokter.pemeriksaan.create', ['reservasi' => $r->id]) }}"
                                   class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition shadow-md shadow-blue-100 flex items-center gap-2">
                                    <span>Lakukan Pemeriksaan</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white p-20 rounded-2xl shadow border border-gray-100 text-center">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Antrean Kosong</p>
            <p class="text-gray-300 text-sm mt-1 font-medium">Belum ada pasien yang mendaftar hari ini.</p>
        </div>
    @endif
</div>

{{-- SCRIPT TIMER & AUTO-REFRESH --}}
<script>
    function updateTimers() {
        const now = new Date().getTime();
        document.querySelectorAll('.timer-display').forEach(el => {
            const startTimeMs = parseInt(el.getAttribute('data-start-ms'));
            const diff = now - startTimeMs;
            const remaining = (3 * 60 * 1000) - diff;

            if (remaining <= 0) {
                el.innerHTML = "TENGGAT WAKTU HABIS! Refresh untuk re-queue.";
                el.classList.add('text-rose-600', 'font-black');
            } else {
                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);
                el.innerHTML = `Sisa waktu: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        });
    }

    // Update timer tiap detik
    setInterval(updateTimers, 1000);
    // Refresh halaman tiap 30 detik untuk menjalankan logika auto-requeue di server
    setTimeout(() => { location.reload(); }, 30000);
</script>
@endsection
