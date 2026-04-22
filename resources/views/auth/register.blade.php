@extends('layouts.guest')

@section('content')
<div class="w-full max-w-md p-8 lg:p-10 bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 my-8">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Daftar Pasien Baru</h2>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-2">Buat Rekam Medis Anda</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
            <ul class="text-[11px] font-bold text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Nama Lengkap</label>
            <input type="text" name="name" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="Sesuai KTP" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Alamat Email</label>
            <input type="email" name="email" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="email@contoh.com" value="{{ old('email') }}" required>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Kata Sandi</label>
            <input type="password" name="password" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="Minimal 6 karakter" required>
        </div>

        <div class="space-y-1.5">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" 
                   class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none"
                   placeholder="Ulangi kata sandi" required>
        </div>

        <div class="pt-6">
            <button type="submit" 
                    class="w-full bg-emerald-600 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 active:scale-95 transition-all outline-none">
                Buat Akun Saya
            </button>
        </div>
    </form>

    <div class="mt-8 text-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Sudah memiliki nomor rekam medis?</p>
        <a href="{{ route('login') }}" 
           class="inline-block text-[11px] font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-800 hover:underline underline-offset-4 transition-all">
            Masuk Ke Sistem
        </a>
    </div>
</div>
@endsection
