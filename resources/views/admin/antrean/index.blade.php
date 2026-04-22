@extends('layouts.app')

@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Monitor Antrean Global</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Status Antrean Lintas Poliklinik Real-Time</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
                <p class="text-[9px] font-black text-blue-400 uppercase">Total Antrean</p>
                <p class="text-lg font-black text-blue-700">{{ $reservasis->count() }}</p>
            </div>
            <div class="bg-green-50 px-4 py-2 rounded-xl border border-green-100">
                <p class="text-[9px] font-black text-green-400 uppercase">Menunggu</p>
                <p class="text-lg font-black text-green-700">{{ $reservasis->where('status', 'menunggu')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- GRID MONITOR --}}
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-6 py-4">Poli / Dokter</th>
                        <th class="px-6 py-4">Nomor Antrean</th>
                        <th class="px-6 py-4">Nama Pasien</th>
                        <th class="px-6 py-4">Waktu Panggil</th>
                        <th class="px-6 py-4 text-center">Status Operasional</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reservasis as $r)
                    <tr class="hover:bg-gray-50/50 transition border-l-4 {{ $r->called_at ? 'border-amber-400' : 'border-transparent' }}">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-700">Poli {{ $r->dokter->poli }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $r->dokter->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded bg-gray-800 text-white font-black text-xs px-2">
                                {{ $r->nomor_antrian_format }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $r->pasien->name }}</td>
                        <td class="px-6 py-4">
                            @if($r->called_at && $r->status == 'menunggu')
                                <div class="space-y-1">
                                    <span class="text-amber-600 text-[10px] uppercase font-black tracking-widest">
                                        Sedang Dipanggil
                                    </span>
                                    <div class="text-[11px] font-mono font-bold text-rose-500 timer-display" 
                                         data-start-ms="{{ \Carbon\Carbon::parse($r->called_at)->timestamp * 1000 }}">
                                        Batas Waktu: 00:00
                                    </div>
                                </div>
                            @elseif($r->status == 'selesai' || $r->status == 'sudah_bayar')
                                <span class="text-green-600 font-bold text-xs uppercase">Selesai diperiksa</span>
                            @else
                                <span class="text-gray-300 italic text-xs">Belum dipanggil</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                @if($r->called_at && $r->status == 'menunggu')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-tighter animate-pulse">
                                        Sedang Dipanggil...
                                    </span>
                                @elseif($r->status == 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">
                                        Pemeriksaan Selesai
                                    </span>
                                @elseif($r->status == 'sudah_bayar')
                                    <span class="px-3 py-1 bg-gray-800 text-white rounded-full text-[10px] font-black uppercase tracking-tighter">
                                        Lunas / Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-tighter">
                                        Antre
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-300 italic">Tidak ada aktivitas antrean hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateTimers() {
        const now = new Date().getTime();
        document.querySelectorAll('.timer-display').forEach(el => {
            const startTimeMs = parseInt(el.getAttribute('data-start-ms'));
            const diff = now - startTimeMs;
            const remaining = (3 * 60 * 1000) - diff;

            if (remaining <= 0) {
                el.innerHTML = "WAKTU HABIS (TIDAK HADIR)";
                el.classList.add('text-rose-600', 'font-black');
            } else {
                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);
                el.innerHTML = `Sisa waktu: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        });
    }

    setInterval(updateTimers, 1000);
    setTimeout(() => { location.reload(); }, 30000);
</script>
@endpush
