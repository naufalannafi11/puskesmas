@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Jadwal Dokter</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Atur ulang sesi operasional poliklinik</p>
        </div>
    </div>

    <form action="{{ route('admin.jadwal-dokter.update', $jadwal->id) }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5 md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Dokter Bertugas</label>
                <select name="dokter_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}" {{ $jadwal->dokter_id == $dokter->id ? 'selected' : '' }}>
                            {{ $dokter->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Poliklinik</label>
                <input type="text" name="poli" value="{{ $jadwal->poli }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Hari Praktik</label>
                <input type="text" name="hari" value="{{ $jadwal->hari }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" value="{{ $jadwal->jam_mulai }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" value="{{ $jadwal->jam_selesai }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:bg-white focus:border-blue-500 transition-all outline-none" required>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 mt-8 flex items-center justify-end gap-3">
            <a href="{{ route('admin.jadwal-dokter.index') }}" class="px-6 py-3 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-800 transition-colors">Batal</a>
            <button type="submit" class="bg-gray-800 text-white font-black text-xs uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-blue-600 active:scale-95 transition-all outline-none shadow-md shadow-gray-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection