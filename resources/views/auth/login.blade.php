@extends('layouts.guest')

@section('content')
<div class="bg-white p-8 rounded shadow w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email"
                   class="w-full border rounded px-3 py-2"
                   required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Login
        </button>
    </form>

    <a href="{{ route('register') }}"
       class="block text-center mt-4 bg-red-600 text-white py-2 rounded hover:bg-red-700">
        Register
    </a>
</div>
@endsection
