<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PinjamIn - Sistem Informasi Peminjaman Alat')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex antialiased">

    <!-- SIDEBAR DARK EMERALD (#047857) -->
    <aside class="w-64 bg-[#047857] text-white flex flex-col justify-between shrink-0 min-h-screen shadow-lg">
        <div>
            <!-- Sidebar Header / Logo PinjamIn -->
            <div class="p-5 flex items-center gap-3.5 border-b border-emerald-800/60">
                <!-- Wrapper Logo Transparan & Ukuran Gambar Logo Maksimal -->
                <div class="w-12 h-12 rounded-2xl bg-white/15 border border-white/40 backdrop-blur-md p-0.5 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('assets/img/pinjaminlogo.svg') }}" alt="Logo PinjamIn" class="w-11 h-11 object-contain drop-shadow-sm">
                </div>
                <div>
                    <h1 class="font-bold text-white text-xl leading-tight tracking-wide">PinjamIn</h1>
                    <span class="text-[10px] uppercase font-extrabold text-emerald-200 tracking-wider">
                        PANEL {{ auth()->user()->role ?? 'USER' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                
                <!-- MENU KHUSUS ADMIN -->
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.user.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.user.*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Kelola User</span>
                    </a>

                    <a href="{{ route('admin.kategori.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.kategori.*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Kelola Kategori</span>
                    </a>

                    <a href="{{ route('admin.alat.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.alat.*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Kelola Alat</span>
                    </a>

                    <a href="{{ route('admin.peminjaman.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.peminjaman*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Kelola Peminjaman</span>
                    </a>

                    <a href="{{ route('admin.pengembalian.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.pengembalian*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/></svg>
                        <span>Kelola Pengembalian</span>
                    </a>

                <!-- MENU KHUSUS PETUGAS -->
                @elseif(auth()->check() && auth()->user()->role === 'petugas')
                    <a href="{{ route('petugas.peminjaman.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('petugas.peminjaman*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Persetujuan Peminjaman</span>
                    </a>

                    <a href="{{ route('petugas.pengembalian.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('petugas.pengembalian*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        <span>Pemantauan Pengembalian</span>
                    </a>

                    <a href="{{ route('petugas.laporan.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('petugas.laporan*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Cetak Laporan</span>
                    </a>

                <!-- MENU KHUSUS PEMINJAM / SISWA -->
                @else
                    <a href="{{ route('peminjam.katalog') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('peminjam.katalog*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Katalog Alat</span>
                    </a>

                    <a href="{{ route('peminjam.riwayat') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('peminjam.riwayat*') ? 'bg-white text-[#065F46] font-bold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Peminjaman</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Logged In User Card (Bawah Sidebar) -->
        <div class="p-3.5 m-3 bg-emerald-900/40 rounded-2xl flex items-center gap-3 border border-emerald-700/50">
            <div class="w-9 h-9 rounded-full bg-white text-[#065F46] font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="overflow-hidden">
                <span class="block text-[9px] text-emerald-200 font-bold uppercase tracking-wider">LOGGED IN AS:</span>
                <h4 class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'User' }}</h4>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        <!-- NAVBAR ATAS -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <h2 class="text-lg font-bold text-gray-900">
                @yield('header-title', 'Dashboard')
            </h2>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-xl text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </header>

        <!-- KONTEN UTAMA HALAMAN -->
        <div class="p-8 space-y-6">
            @yield('content')
        </div>

    </main>

</body>
</html>