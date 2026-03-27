<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puskesmas</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-green-600 text-white">
        <div class="p-6 text-xl font-bold border-b border-white">
            Puskesmas Wonokerto
        </div>

        <nav class="p-4 space-y-2">

            @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Dashboard Admin
        </a>

        <a href="{{route('admin.pasien.index') }}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Kelola Pasien
        </a>

        <a href="{{ route('admin.dokter.index') }}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Kelola Dokter
        </a>

        <a href="{{route('admin.rekam_medis.index')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Kelola Rekam Medis
        </a>

        <a href="{{route('admin.obat.index')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Kelola Obat
        </a>

        <a href="{{ route('admin.pembayaran.index') }}"
            class="block px-4 py-2 rounded hover:bg-green-500">
            Pembayaran
        </a>

    @elseif(auth()->user()->role === 'dokter')
        <a href="{{ route('dokter.dashboard') }}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Dashboard Dokter
        </a>

        <a href="{{route ('dokter.pemeriksaan.index')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Pemeriksaan Pasien
        </a>

        <a href="{{route ('dokter.pemeriksaan.riwayat')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Rekam Medis
        </a>

    @elseif(auth()->user()->role === 'pasien')
        <a href="{{ route('pasien.dashboard') }}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Dashboard Pasien
        </a>

        <a href="{{route ('pasien.reservasi.create')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Buat Reservasi
        </a>

        <a href="{{route ('pasien.riwayat')}}"
           class="block px-4 py-2 rounded hover:bg-green-500">
            Riwayat Berobat
        </a>
    @endif

@else
    <a href="{{ route('login') }}"
       class="block px-4 py-2 rounded hover:bg-green-500">
        Login
    </a>
@endauth

        </nav>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        <div class="flex items-center mb-6">
    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Logout
        </button>
    </form>
</div>


        @yield('content')
    </main>

</div>

</body>
</html>
