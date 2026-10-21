@extends('layouts.app')

@section('title', 'Cetak Laporan - PinjamIn Petugas')
@section('header-title', 'Laporan Peminjaman & Pengembalian Alat')

@section('content')

    <!-- Card Filter Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Filter Laporan Peminjaman</h3>

        <form action="{{ route('petugas.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Status Peminjaman -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">STATUS PEMINJAMAN</label>
                <select name="status" class="w-full px-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="telat" {{ request('status') === 'telat' ? 'selected' : '' }}>Telat</option>
                </select>
            </div>

            <!-- Dari Tanggal -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">DARI TANGGAL (PINJAM)</label>
                <input type="date" name="tgl_awal" value="{{ request('tgl_awal') }}"
                       class="w-full px-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">SAMPAI TANGGAL (PINJAM)</label>
                <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}"
                       class="w-full px-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Submit Filter Button -->
            <div>
                <button type="submit" class="w-full py-2.5 bg-[#0F172A] hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Card Hasil Rekap Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header & Tombol Cetak -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Hasil Rekap Laporan Peminjaman & Pengembalian</h3>
                <p class="text-xs text-gray-400 mt-0.5">Laporan resmi rekapitulasi penggunaan dan sirkulasi alat.</p>
            </div>

            <!-- Tombol Cetak Laporan -->
            <a href="{{ route('petugas.laporan.cetak', request()->all()) }}" target="_blank"
               class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Print Laporan
            </a>
        </div>

        <!-- Tabel Laporan -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 w-12">NO</th>
                        <th class="py-4 px-6">PEMINJAM</th>
                        <th class="py-4 px-6">TGL PINJAM</th>
                        <th class="py-4 px-6">RENCANA KEMBALI</th>
                        <th class="py-4 px-6 text-center">STATUS</th>
                        <th class="py-4 px-6">DETAIL ALAT</th>
                        <th class="py-4 px-6 text-right">DENDA</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($laporans as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition align-top">
                            <td class="py-4 px-6 text-gray-400 font-medium">
                                {{ method_exists($laporans, 'firstItem') ? $laporans->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 whitespace-nowrap">
                                {{ $item->user->name ?? 'User' }}
                            </td>
                            <td class="py-4 px-6 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                {{ $item->tgl_pinjam }} 00:00:00
                            </td>
                            <td class="py-4 px-6 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                {{ $item->tgl_kembali_plan }} 00:00:00
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold inline-block capitalize
                                    @if($item->status === 'dipinjam') bg-blue-100 text-blue-700
                                    @elseif(in_array($item->status, ['dikembalikan', 'selesai'])) bg-emerald-100 text-emerald-700
                                    @elseif($item->status === 'telat') bg-rose-100 text-rose-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-gray-800 font-medium">
                                    @foreach($item->detailPinjam as $d)
                                        {{ $d->alat->nama_alat ?? 'Alat' }} ({{ $d->jumlah }}){{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold whitespace-nowrap
                                {{ ($item->pengembalian->denda ?? 0) > 0 ? 'text-rose-600' : 'text-gray-900' }}">
                                Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 text-xs">Belum ada data laporan sesuai filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($laporans, 'links'))
            <div class="p-4 border-t border-gray-100">
                {{ $laporans->links() }}
            </div>
        @endif

    </div>

@endsection