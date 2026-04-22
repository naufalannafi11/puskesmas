@extends('layouts.app')

@section('content')

<div class="space-y-6 max-w-5xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Pemeriksaan Medis</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Input Diagnosa & Pengobatan Pasien</p>
        </div>
        <a href="{{ route('dokter.pemeriksaan.index') }}" class="text-gray-400 hover:text-gray-600 transition p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </div>

    {{-- INFORMASI PASIEN --}}
    <div class="bg-gray-800 text-white rounded-2xl shadow-xl p-8 border border-gray-700 relative overflow-hidden">
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Pasien</p>
                <p class="text-xl font-bold tracking-tight">{{ $reservasi->pasien->name }}</p>
                <p class="text-xs text-gray-500 mt-1 italic">No. Antrean: {{ $reservasi->nomor_antrian }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Kunjungan</p>
                <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Keluhan Utama</p>
                <p class="text-sm font-medium text-gray-300 leading-relaxed">{{ $reservasi->keluhan }}</p>
            </div>
        </div>
    </div>

    {{-- FORM UTAMA --}}
    <form action="{{ route('dokter.pemeriksaan.store', $reservasi->id) }}" method="POST" class="space-y-6">
        @csrf

        {{-- BLOCK 1: PEMERIKSAAN DASAR --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-50 pb-4 mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Pemeriksaan & Anamnesis</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Anamnesis <span class="text-red-500">*</span></label>
                    <textarea name="anamnesis" rows="3" required placeholder="Riwayat keluhan pasien..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemeriksaan Fisik <span class="text-red-500">*</span></label>
                    <textarea name="pemeriksaan" rows="3" required placeholder="Tanda vital, inspeksi, palpasi..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-blue-500 transition-all outline-none resize-none"></textarea>
                </div>
            </div>
        </div>

        {{-- BLOCK 2: DIAGNOSA --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-50 pb-4 mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Diagnosa & Kode Penyakit</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih Diagnosa Penyakit (ICD-10) <span class="text-red-500">*</span></label>
                    
                    {{-- SEARCHABLE SELECT --}}
                    <select name="kode_icd" id="icd_selector" required
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-rose-500 transition-all outline-none">
                        <option value="">-- Cari atau Pilih Penyakit --</option>
                        @foreach($penyakits as $p)
                            <option value="{{ $p->kode_icd }}" data-nama="{{ $p->nama_penyakit }}">
                                {{ $p->kode_icd }} - {{ $p->nama_penyakit }}
                            </option>
                        @endforeach
                        <option value="LAINNYA">LAINNYA (Input Manual)</option>
                    </select>

                    {{-- HIDDEN/VISIBLE TEXT INPUT FOR FINAL DIAGNOSIS --}}
                    <div id="manual_diagnosis_box" class="space-y-2 mt-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Diagnosa / Nama Penyakit</label>
                        <input type="text" name="diagnosis" id="diagnosis_text" required placeholder="Nama penyakit akan terisi otomatis..."
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-rose-500 transition-all outline-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- SCRIPT UNTUK OTOMASI DIAGNOSA --}}
        <script>
            document.getElementById('icd_selector').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const namaPenyakit = selectedOption.getAttribute('data-nama');
                const diagInput = document.getElementById('diagnosis_text');

                if (this.value === 'LAINNYA') {
                    diagInput.value = '';
                    diagInput.placeholder = 'Ketik diagnosa manual di sini...';
                    diagInput.readOnly = false;
                    diagInput.focus();
                } else if (this.value !== '') {
                    diagInput.value = namaPenyakit;
                    diagInput.readOnly = true;
                } else {
                    diagInput.value = '';
                    diagInput.readOnly = false;
                }
            });
        </script>

        {{-- BLOCK 3: TERAPI & OBAT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-50 pb-4 mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Rencana Terapi & Resep Obat</h3>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tindakan / Prosedur</label>
                    <textarea name="tindakan" rows="2" placeholder="Tindakan yang dilakukan di puskesmas..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-green-500 transition-all outline-none resize-none"></textarea>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span>Input Resep Terstruktur</span>
                        <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-full text-[8px] font-black tracking-tighter">STOK TERHUBUNG</span>
                    </label>
                    
                    <div id="medicine-container" class="space-y-3">
                        <div class="flex gap-2 items-center medicine-row group">
                            <div class="flex-1 relative">
                                <select name="obat_ids[]" class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-green-500 transition-all outline-none appearance-none">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach($obats as $obat)
                                        <option value="{{ $obat->id }}" {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }} {{ $obat->satuan ?? 'Unit' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="number" name="jumlahs[]" placeholder="Qty" min="1" class="w-24 bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-green-500 transition-all outline-none">
                            <button type="button" onclick="removeRow(this)" class="p-2 text-gray-300 hover:text-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="button" onclick="addRow()" 
                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-700 transition px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Item Obat</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- BLOCK 4: TINDAK LANJUT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-50 pb-4 mb-6">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Tindak Lanjut & Penunjang</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemeriksaan Lab / Penunjang</label>
                    <textarea name="pemeriksaan_lab" rows="2" placeholder="Hasil laboratorium, EKG, Rongten..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-amber-500 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rencana Tindak Lanjut</label>
                    <textarea name="rencana_tindak_lanjut" rows="2" placeholder="Istirahat, kontrol kembali, dsb..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-amber-500 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rujukan / Catatan Tambahan</label>
                    <textarea name="rujukan" rows="2" placeholder="Jika pasien perlu dirujuk ke rumah sakit..."
                        class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-amber-500 transition-all outline-none resize-none"></textarea>
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex justify-end pt-4">
            <button type="submit"
                class="bg-green-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 hover:-translate-y-1 transition-all duration-300">
                Finalisasi & Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>

{{-- DYNAMIC ROW SCRIPT --}}
<script>
    function addRow() {
        const container = document.getElementById('medicine-container');
        const row = container.querySelector('.medicine-row').cloneNode(true);
        row.querySelector('select').value = "";
        row.querySelector('input').value = "";
        container.appendChild(row);
    }
    function removeRow(btn) {
        const container = document.getElementById('medicine-container');
        if (container.children.length > 1) {
            btn.closest('.medicine-row').remove();
        } else {
            alert('Minimal harus ada 1 baris input obat.');
        }
    }
</script>

@endsection
