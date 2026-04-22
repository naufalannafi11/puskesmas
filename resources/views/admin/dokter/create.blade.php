@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Registrasi Dokter Baru</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Tambahkan tenaga medis ke dalam sistem</p>
        </div>
    </div>

    <form method="POST" action="{{route('admin.dokter.store') }}" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Nama Lengkap & Gelar</label>
            <input name="name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" required>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Penugasan Poliklinik</label>
            <select name="poli" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" required>
                <option value="">-- Pilih Penempatan --</option>
                <option value="Umum">Poli Umum</option>
                <option value="Gigi">Poli Gigi</option>
                <option value="KIA">Poli KIA</option>
                <option value="KB">Poli KB</option>
                <option value="Imunisasi">Poli Imunisasi</option>
            </select>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Alamat Email / Akun</label>
            <input name="email" type="email" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" required>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Kata Sandi (Password)</label>
            <input name="password" type="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" required>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.dokter.index') }}" class="px-6 py-3 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-800 transition-colors">Batal</a>
            <button type="submit" class="bg-gray-800 text-white font-black text-xs uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-blue-600 active:scale-95 transition-all outline-none shadow-md shadow-gray-200">
                Simpan Dokter
            </button>
        </div>
    </form>
</div>
@endsection