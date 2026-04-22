@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Jadwal Praktik</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Pengaturan Jam Layanan Dokter</p>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM TAMBAH (CARD STYLE) --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-6">Tambah Jadwal Baru</h3>
        <form action="{{ route('admin.jadwal-dokter.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <select name="dokter_id" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl text-sm p-3 transition" required>
                    <option value="">Pilih Dokter</option>
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}">{{ $dokter->name }} ({{ $dokter->poli }})</option>
                    @endforeach
                </select>
                <input type="text" name="poli" placeholder="Nama Poli" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl text-sm p-3 transition" required>
                <select name="hari" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl text-sm p-3 transition" required>
                    <option value="">Pilih Hari</option>
                    <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
                </select>
                <input type="time" name="jam_mulai" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl text-sm p-3 transition" required>
                <input type="time" name="jam_selesai" class="border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl text-sm p-3 transition" required>
            </div>
            <button class="mt-4 bg-gray-800 text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-black transition-all shadow-md active:scale-95">
                Simpan Jadwal
            </button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-4">Nama Dokter</th>
                    <th class="px-6 py-4">Poliklinik</th>
                    <th class="px-6 py-4">Hari Layanan</th>
                    <th class="px-6 py-4 text-center">Jam Operasional</th>
                    <th class="px-6 py-4 text-center">Aksi Operasional</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($jadwals as $j)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $j->dokter->name }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs italic">{{ $j->poli }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-amber-50 text-amber-700 rounded-full font-bold text-[10px]">
                                {{ $j->hari }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400 font-mono text-[11px]">
                            {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.jadwal-dokter.edit', $j->id) }}"
                                   class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition shadow-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.jadwal-dokter.destroy', $j->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Hapus jadwal ini?')"
                                            class="px-4 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-bold hover:bg-red-100 transition shadow-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-300 italic font-medium">Belum ada jadwal dokter yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection