<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puskesmas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-green-700 text-white flex flex-col h-screen sticky top-0 shadow-xl overflow-hidden">

            <!-- LOGO -->
            <div class="p-8 text-2xl font-black border-b border-green-600/50 tracking-tighter uppercase">
                PUSKESMAS WONOKERTO
            </div>

            <!-- MENU -->
            <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto custom-scrollbar">

                @auth

                    {{-- ================= ADMIN ================= --}}
                    @if(auth()->user()->role === 'admin')
                        <div class="text-[10px] font-bold text-green-300 uppercase tracking-[0.2em] px-4 mb-2 mt-4 opacity-70">Main Menu</div>

                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('admin.pasien.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.pasien.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Kelola Pasien
                        </a>

                        <a href="{{ route('admin.dokter.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dokter.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Kelola Dokter
                        </a>

                        <a href="{{ route('admin.jadwal-dokter.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.jadwal-dokter.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Jadwal Dokter
                        </a>

                        <a href="{{ route('admin.rekam_medis.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.rekam_medis.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Rekam Medis
                        </a>

                        <a href="{{ route('admin.obat.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.obat.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Obat
                        </a>

                        <a href="{{ route('admin.antrean.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.antrean.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Monitor Antrean
                        </a>

                        <a href="{{ route('admin.pembayaran.index') }}"
                            class="flex justify-between items-center px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            <span>Pembayaran</span>
                            @if(isset($jumlahBelumBayar) && $jumlahBelumBayar > 0)
                                <span class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">
                                    {{ $jumlahBelumBayar }}
                                </span>
                            @endif
                        </a>

                        <div class="text-[10px] font-bold text-green-300 uppercase tracking-[0.2em] px-4 mb-2 mt-8 opacity-70">Analytics</div>
                        
                        <a href="{{ route('admin.dataset.import') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dataset.import') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Import Dataset
                        </a>

                        <a href="{{ route('admin.prediksi.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.prediksi.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Analisis Prediksi AI
                        </a>

                        <a href="{{ route('admin.laporan.index') }}"
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-white/20 shadow-inner font-bold' : 'hover:bg-white/10' }}">
                            Laporan & Statistik
                        </a>

                        {{-- ================= DOKTER ================= --}}
                    @elseif(auth()->user()->role === 'dokter')
                        <a href="{{ route('dokter.dashboard') }}" class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dokter.dashboard') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">Dashboard</a>
                        <a href="{{ route('dokter.pemeriksaan.index') }}" class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dokter.pemeriksaan.*') && !request()->routeIs('dokter.pemeriksaan.riwayat') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">Pemeriksaan</a>
                        <a href="{{ route('dokter.jadwal.index') }}" class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dokter.jadwal.*') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">Jadwal Praktik</a>
                        <a href="{{ route('dokter.pemeriksaan.riwayat') }}" class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dokter.pemeriksaan.riwayat') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">Rekam Medis</a>

                        {{-- ================= PASIEN ================= --}}
                    @elseif(auth()->user()->role === 'pasien')
                        <a href="{{ route('pasien.dashboard') }}" 
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('pasien.dashboard') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('pasien.jadwal.index') }}" 
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('pasien.jadwal.*') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">
                            Jadwal Dokter
                        </a>
                        <a href="{{ route('pasien.reservasi.create') }}" 
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('pasien.reservasi.*') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">
                            Buat Reservasi
                        </a>
                        <a href="{{ route('pasien.riwayat') }}" 
                            class="block px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('pasien.riwayat') ? 'bg-white/20 font-bold shadow-inner' : 'hover:bg-white/10' }}">
                            Riwayat Berobat
                        </a>
                    @endif

                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl transition-all duration-200 bg-white/10 hover:bg-white/20 text-center font-bold mt-6">
                        Login Portalkes
                    </a>
                @endauth

            </nav>

            <!-- SIDEBAR FOOTER -->
            <div class="p-6 border-t border-green-600/50">
                <div class="text-[9px] text-green-300 uppercase tracking-[0.3em] font-bold text-center opacity-60">
                    &copy; {{ date('Y') }} System
                </div>
            </div>

        </aside>


        <!-- CONTENT -->
        <main class="flex-1 bg-gray-50/50 p-8">

            <!-- HEADER CONTENT -->
            <div class="flex justify-between items-start mb-10">
                {{-- KIRI: IDENTITY --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">
                        Hak Akses: {{ strtoupper(auth()->user()->role ?? 'Guest') }}
                    </p>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </h2>
                    @if(auth()->user() && auth()->user()->role === 'pasien' && auth()->user()->no_rm)
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">
                        No. RM: <span class="text-gray-600">{{ auth()->user()->no_rm }}</span>
                    </p>
                    @endif
                </div>

                {{-- KANAN: ACTIONS --}}
                @auth
                    <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm">
                        <a href="{{ route('profile.edit') }}" 
                            title="Edit Profil"
                            class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>

                        <div class="w-px h-6 bg-gray-100 mx-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button title="Keluar Sesi" class="w-10 h-10 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200 active:scale-90 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            {{-- GLOBAL NOTIFICATIONS --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CONTENT -->
            @yield('content')

        </main>

    </div>

</body>

</html>