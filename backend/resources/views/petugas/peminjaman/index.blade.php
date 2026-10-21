@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - PinjamIn Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')

    <!-- Flash Message -->
    @if(session('success'))
        <div class="mb-5 bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 bg-rose-100 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Top Summary Cards Grid (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <!-- Card 1: Menunggu Verifikasi -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">MENUNGGU VERIFIKASI</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $statMenunggu ?? count($peminjamans->where('status', 'diajukan')) }} Pengajuan
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <!-- Card 2: Disetujui Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">DISETUJUI HARI INI</span>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                    {{ $statDisetujuiHariIni ?? 4 }} Peminjaman
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-100/70 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <!-- Card 3: Total Alat Dipinjam -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL ALAT DIPINJAM</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $statTotalAlat ?? 14 }} Item
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 4a1 1 0 00-2 0v1h2V4zm0 3H3v1h2V7zm0 3H3v1h2v-1zm0 3H3v1h2v-1zm2-9h10v12H7V4zm2 2v2h6V6H9zm0 4v2h6v-2H9z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header Table & Search Toolbar -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Menunggu Verifikasi Persetujuan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Periksa rincian alat dan tanggal peminjaman sebelum menyetujui.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-64 md:w-72">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                        class="w-full pl-9 pr-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </form>
        </div>

        <!-- Table Pengajuan Peminjaman -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">PEMINJAM</th>
                        <th class="py-4 px-6">TANGGAL PINJAM</th>
                        <th class="py-4 px-6">RENCANA KEMBALI</th>
                        <th class="py-4 px-6">DETAIL ALAT</th>
                        <th class="py-4 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-gray-50/80 transition align-top">
                            <!-- Peminjam -->
                            <td class="py-4 px-6 font-bold text-gray-900 whitespace-nowrap">
                                <div>{{ $peminjaman->user->name ?? 'User' }}</div>
                                <span class="text-[10px] text-gray-400 font-mono font-normal">NIM: {{ $peminjaman->user->nim ?? '22051012' }}</span>
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 font-mono text-[11px] whitespace-nowrap">
                                <div class="text-gray-800 font-semibold">{{ $peminjaman->tgl_pinjam }}</div>
                                <div class="text-gray-400 text-[10px]">08:00:00</div>
                            </td>

                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 font-mono text-[11px] whitespace-nowrap">
                                <div class="text-gray-800 font-semibold">{{ $peminjaman->tgl_kembali_plan }}</div>
                                <div class="text-gray-400 text-[10px]">16:00:00</div>
                            </td>

                            <!-- Detail Alat List -->
                            <td class="py-4 px-6">
                                <ul class="space-y-1 list-disc list-inside text-gray-800 font-medium">
                                    @foreach($peminjaman->detailPinjam as $detail)
                                        <li>
                                            <strong class="font-bold text-gray-900">{{ $detail->alat->nama_alat ?? 'Alat' }}</strong> 
                                            <span class="text-gray-400 font-normal text-[10px]">(Jumlah: {{ $detail->jumlah }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Aksi: Setujui & Tolak -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Form Setujui -->
                                    <form action="{{ route('petugas.peminjaman.setujui', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Form Tolak -->
                                    <form action="{{ route('petugas.peminjaman.tolak', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin menolak peminjaman ini?')">
                                        @csrf
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold px-3.5 py-2 rounded-xl transition flex items-center gap-1">
                                            <span class="text-sm">×</span> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-xs">Tidak ada pengajuan peminjaman baru yang menunggu verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($peminjamans, 'links'))
            <div class="p-4 border-t border-gray-100">
                {{ $peminjamans->links() }}
            </div>
        @endif

    </div>

@endsection