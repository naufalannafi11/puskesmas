@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md p-8 lg:p-10 bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Masuk Sistem</h2>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-2">Sistem Informasi Puskesmas</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
            <p class="text-xs font-bold text-red-600">{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Email Pengguna</label>
            <input type="email" name="email" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="Masukkan alamat email"
                   required autofocus>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Kata Sandi</label>
            <input type="password" name="password" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="••••••••"
                   required>
        </div>

        <div class="pt-4 space-y-4">
            <button type="submit" 
                    class="w-full bg-emerald-600 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 active:scale-95 transition-all outline-none">
                Masuk Sekarang
            </button>
            
            <div class="relative flex items-center justify-center py-2">
                <div class="w-full h-px bg-gray-100"></div>
                <span class="absolute bg-white px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest">Atau</span>
            </div>

            <a href="{{ route('register') }}" 
               class="block w-full text-center bg-white text-gray-600 border border-gray-200 font-black text-xs uppercase tracking-widest py-4 rounded-2xl hover:bg-gray-50 hover:border-gray-300 active:scale-95 transition-all outline-none">
                Daftar Pasien Baru
            </a>
        </div>
    </form>
</div>
@endsection
