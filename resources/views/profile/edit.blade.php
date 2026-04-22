@extends('layouts.app')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto py-8">
    {{-- HEADER --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Pengaturan Profil</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Kelola informasi akun dan keamanan Anda</p>
        </div>
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center font-black text-xl">
            {{ substr($user->name, 0, 1) }}
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- SECTION: INFORMASI AKUN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Informasi Akun</h3>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm font-bold focus:border-green-500 outline-none transition-all @error('name') border-red-200 @enderror">
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm font-bold focus:border-green-500 outline-none transition-all @error('email') border-red-200 @enderror">
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: KEAMANAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Keamanan (Ganti Password)</h3>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" 
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm font-bold focus:border-green-500 outline-none transition-all @error('current_password') border-red-200 @enderror">
                        @error('current_password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Password Baru</label>
                        <input type="password" name="new_password" 
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm font-bold focus:border-green-500 outline-none transition-all @error('new_password') border-red-200 @enderror">
                        @error('new_password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" 
                            class="w-full bg-gray-50 border-gray-100 border-2 rounded-xl px-4 py-3 text-sm font-bold focus:border-green-500 outline-none transition-all">
                    </div>
                </div>
                
                <p class="text-[10px] text-gray-400 italic font-medium">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
        </div>

        {{-- BUTTON ACTIONS --}}
        <div class="flex justify-end gap-3">
            <a href="javascript:history.back()" class="px-8 py-3 bg-gray-100 text-gray-500 rounded-xl text-xs font-black uppercase hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-8 py-3 bg-gray-800 text-white rounded-xl text-xs font-black uppercase hover:bg-black transition-all shadow-lg active:scale-95">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
