@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-8 space-y-8">
    {{-- HEADER --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Buat Reservasi Kunjungan</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Daftarkan diri Anda untuk konsultasi medis</p>
        </div>
    </div>

    {{-- RESERVASI FORM --}}
    <form method="POST" action="{{ route('pasien.reservasi.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
            <div class="p-8 space-y-6">
                {{-- BARIS 1: POLI & TANGGAL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih unit Poliklinik</label>
                        <select name="poli" id="poliSelect" required
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white focus:border-green-500 transition-all outline-none appearance-none">
                            <option value="">-- Pilih Poliklinik --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli }}" {{ request('poli') == $poli ? 'selected' : '' }}>{{ $poli }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih Tanggal Kunjungan</label>
                        <input type="date" name="tanggal" id="tanggalSelect" required min="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white focus:border-green-500 transition-all outline-none">
                    </div>
                </div>

                {{-- BARIS 2: DOKTER --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih Tenaga Medis (Dokter)</label>
                    <select name="dokter_id" id="dokterSelect" required
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white focus:border-green-500 transition-all outline-none appearance-none disabled:opacity-50">
                        <option value="">-- Pilih Dokter Tersedia --</option>
                    </select>
                    <p id="dokterHint" class="text-[10px] text-gray-300 font-medium italic">Silakan pilih poliklinik dan tanggal terlebih dahulu</p>
                </div>

                {{-- BARIS 3: KELUHAN --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Keluhan Utama</label>
                    <textarea name="keluhan" rows="4" required placeholder="Jelaskan secara singkat gejala atau keluhan yang Anda rasakan..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-2xl px-5 py-4 text-sm font-bold focus:bg-white focus:border-green-500 transition-all outline-none resize-none"></textarea>
                </div>
            </div>

            {{-- FOOTER FORM --}}
            <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex justify-end items-center gap-4">
                <a href="{{ route('pasien.dashboard') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">Batal</a>
                <button type="submit" 
                    class="bg-gray-800 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gray-200 hover:bg-black hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    Konfirmasi Reservasi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const poliSelect = document.getElementById('poliSelect');
    const tanggalSelect = document.getElementById('tanggalSelect');
    const dokterSelect = document.getElementById('dokterSelect');
    const dokterHint = document.getElementById('dokterHint');

    function updateDokterStatus() {
        if (!poliSelect.value || !tanggalSelect.value) {
            dokterSelect.disabled = true;
            dokterHint.textContent = 'Pilih poliklinik dan tanggal untuk melihat jadwal dokter.';
            return;
        }
        
        dokterSelect.disabled = false;
        dokterSelect.innerHTML = '<option value="">Sedang Mencari Jadwal...</option>';

        fetch(`/get-dokter-by-jadwal?poli=${poliSelect.value}&tanggal=${tanggalSelect.value}`)
            .then(res => res.json())
            .then(data => {
                dokterSelect.innerHTML = '<option value="">-- Pilih Dokter Tersedia --</option>';
                
                if (data.length === 0) {
                    dokterSelect.innerHTML += '<option disabled>Maaf, tidak ada dokter bertugas di waktu tersebut</option>';
                    dokterHint.textContent = 'Coba pilih tanggal atau poliklinik lain.';
                } else {
                    data.forEach(dokter => {
                        dokterSelect.innerHTML += `<option value="${dokter.id}">${dokter.name}</option>`;
                    });
                    dokterHint.textContent = `${data.length} Dokter bertugas ditemukan.`;
                }

                // Jika ada pre-selected dokter dari route
                @if(request('dokter_id'))
                    const preSelectedId = "{{ request('dokter_id') }}";
                    if (dokterSelect.querySelector(`option[value="${preSelectedId}"]`)) {
                        dokterSelect.value = preSelectedId;
                    }
                @endif
            })
            .catch(() => {
                dokterSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    poliSelect.addEventListener('change', updateDokterStatus);
    tanggalSelect.addEventListener('change', updateDokterStatus);

    // Initial check (untuk pre-selected data dari halaman Jadwal Dokter)
    if(poliSelect.value) updateDokterStatus();
</script>

@endsection