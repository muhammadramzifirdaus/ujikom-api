@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - PinjamIn')
@section('header-title', 'Riwayat Pengajuan Peminjaman')

@section('content')

    <!-- Flash Message -->
    @if(session('success'))
        <div class="mb-5 bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Top Summary Cards (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <!-- Card 1: Aktif Dipinjam -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">AKTIF DIPINJAM</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $statAktif ?? count($peminjamans->whereIn('status', ['diajukan', 'dipinjam'])) }} Transaksi
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            </div>
        </div>

        <!-- Card 2: Selesai Dikembalikan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">SELESAI DIKEMBALIKAN</span>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                    {{ $statSelesai ?? count($peminjamans->whereIn('status', ['dikembalikan', 'selesai'])) }} Transaksi
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-100/70 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </div>
        </div>

        <!-- Card 3: Total Denda Terbayar -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL DENDA TERBAYAR</span>
                <h3 class="text-2xl font-bold text-rose-600 font-mono mt-1">
                    Rp {{ number_format($statTotalDenda ?? 482500, 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Container Card Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header Toolbar -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Transaksi Saya</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pantau status persetujuan, batas pengembalian, dan rincian denda.</p>
            </div>

            <a href="{{ route('peminjam.katalog') }}" 
               class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5 whitespace-nowrap">
                <span class="text-sm font-bold">+</span> Ajukan Baru
            </a>
        </div>

        <!-- Tabel Riwayat Saya -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">ALAT YANG DIPINJAM</th>
                        <th class="py-4 px-6">TANGGAL PINJAM / BATAS</th>
                        <th class="py-4 px-6 text-center">STATUS</th>
                        <th class="py-4 px-6">DETAIL PENGEMBALIAN</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-gray-50/80 transition align-top">
                            <!-- Alat Badges -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1.5 max-w-sm">
                                    @foreach($peminjaman->detailPinjam as $detail)
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900">{{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                                            <span class="bg-gray-100 text-gray-600 font-medium px-2 py-0.5 rounded text-[10px]">{{ $detail->jumlah }} Unit</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Tanggal Pinjam / Batas -->
                            <td class="py-4 px-6 font-mono text-[11px] whitespace-nowrap">
                                <div class="text-gray-900 font-bold">{{ $peminjaman->tgl_pinjam }}</div>
                                <div class="text-gray-400 text-[10px] mt-0.5">Batas: {{ $peminjaman->tgl_kembali_plan }}</div>
                            </td>

                            <!-- Badge Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold inline-block capitalize
                                    @if($peminjaman->status === 'diajukan') bg-blue-100 text-blue-700
                                    @elseif($peminjaman->status === 'dipinjam') bg-blue-100 text-blue-700
                                    @elseif(in_array($peminjaman->status, ['dikembalikan', 'selesai'])) bg-emerald-100 text-emerald-700
                                    @elseif($peminjaman->status === 'telat') bg-rose-100 text-rose-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>

                            <!-- Detail Pengembalian -->
                            <td class="py-4 px-6 text-[11px]">
                                @if($peminjaman->pengembalian)
                                    <div class="space-y-0.5">
                                        <div class="text-gray-700">
                                            <strong>Tgl Kembali:</strong> <span class="font-mono text-gray-900 font-bold">{{ $peminjaman->pengembalian->tgl_kembali }}</span>
                                        </div>
                                        <div class="text-gray-700">
                                            <strong>Kondisi:</strong> 
                                            <span class="font-bold 
                                                @if(Str::contains($peminjaman->pengembalian->kondisi_kembali, 'Baik')) text-emerald-600
                                                @else text-amber-600 @endif">
                                                {{ $peminjaman->pengembalian->kondisi_kembali }}
                                            </span>
                                        </div>
                                        <div class="text-gray-700">
                                            <strong>Total Denda:</strong> 
                                            <span class="font-bold text-rose-600">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 text-xs">Belum ada riwayat transaksi peminjaman.</td>
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