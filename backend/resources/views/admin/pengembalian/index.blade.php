@extends('layouts.app')

@section('title', 'Kelola Pengembalian - PinjamIn Admin')
@section('header-title', 'Manajemen Pengembalian Alat')

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

    <!-- Card Main Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header & Search Toolbar -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Transaksi Pengembalian</h3>
                <p class="text-xs text-gray-400 mt-0.5">Verifikasi kelengkapan barang, cek denda keterlambatan, dan kerusakan fisik</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-64 md:w-72">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam / status..."
                        class="w-full pl-9 pr-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
                <button type="submit" class="bg-[#0F172A] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Tabel Pengembalian -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">PEMINJAM</th>
                        <th class="py-4 px-6">ALAT & JUMLAH</th>
                        <th class="py-4 px-6">TGL PINJAM / BATAS</th>
                        <th class="py-4 px-6 text-center">STATUS</th>
                        <th class="py-4 px-6">DETAIL PENGEMBALIAN</th>
                        <th class="py-4 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-gray-50/80 transition align-top">
                            <!-- Peminjam -->
                            <td class="py-4 px-6 font-bold text-gray-900 whitespace-nowrap">
                                {{ $peminjaman->user->name ?? 'User Terhapus' }}
                            </td>

                            <!-- Alat & Jumlah (Badges) -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1.5 max-w-xs">
                                    @foreach($peminjaman->detailPinjam as $detail)
                                        <span class="inline-flex items-center justify-between bg-gray-100/80 border border-gray-200/60 text-gray-800 px-2.5 py-1 rounded-lg text-[11px] font-medium">
                                            <span>{{ $detail->alat->nama_alat ?? 'Alat Terhapus' }}</span>
                                            <span class="text-gray-400 text-[10px] ml-1">({{ $detail->jumlah }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Tgl Pinjam / Batas -->
                            <td class="py-4 px-6 font-mono text-[11px] whitespace-nowrap">
                                <div class="text-gray-500">
                                    Pinjam: <span class="text-gray-800 font-medium">{{ $peminjaman->tgl_pinjam }}</span>
                                </div>
                                <div class="text-amber-700 mt-0.5">
                                    Batas: <span class="font-bold">{{ $peminjaman->tgl_kembali_plan }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold inline-block capitalize
                                    @if($peminjaman->status === 'dipinjam') bg-purple-100 text-purple-700
                                    @elseif(in_array($peminjaman->status, ['dikembalikan', 'selesai'])) bg-emerald-100 text-emerald-700
                                    @elseif($peminjaman->status === 'telat') bg-amber-100 text-amber-800
                                    @else bg-rose-100 text-rose-700 @endif">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>

                            <!-- Detail Pengembalian -->
                            <td class="py-4 px-6 text-[11px]">
                                @if($peminjaman->pengembalian)
                                    <div class="space-y-0.5">
                                        <div class="text-gray-600">
                                            <strong>Tgl Kembali:</strong> <span class="font-mono text-gray-800">{{ $peminjaman->pengembalian->tgl_kembali }}</span>
                                        </div>
                                        <div class="text-gray-600">
                                            <strong>Kondisi:</strong> 
                                            <span class="font-bold text-amber-600">{{ $peminjaman->pengembalian->kondisi_kembali }}</span>
                                        </div>
                                        <div class="text-gray-600">
                                            <strong>Total Denda:</strong> 
                                            <span class="font-bold text-rose-600">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-gray-400 text-[10px] italic mt-1">
                                            Penerima: {{ $peminjaman->pengembalian->petugas->name ?? 'Admin' }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-amber-600 font-semibold italic text-[11px]">Belum dikembalikan</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($peminjaman->status === 'dipinjam')
                                    <a href="{{ route('admin.pengembalian.create', $peminjaman->id) }}" 
                                       class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-emerald-600/20 transition inline-block">
                                        Proses Kembali
                                    </a>
                                @else
                                    <span class="bg-gray-100 text-gray-400 text-xs font-bold px-3 py-1.5 rounded-xl cursor-not-allowed inline-block">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-xs">Belum ada transaksi yang perlu diproses pengembalian.</td>
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