@extends('layouts.guest')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Pasien</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input type="text" name="name"
               placeholder="Nama Lengkap"
               class="w-full border p-2 rounded"
               required>

        <input type="email" name="email"
               placeholder="Email"
               class="w-full border p-2 rounded"
               required>

        <input type="password" name="password"
               placeholder="Password"
               class="w-full border p-2 rounded"
               required>

        <input type="password" name="password_confirmation"
               placeholder="Konfirmasi Password"
               class="w-full border p-2 rounded"
               required>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Daftar
        </button>
    </form>

    <p class="text-sm mt-4 text-center">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-blue-600">
            Login
        </a>
    </p>
</div>
@endsection
