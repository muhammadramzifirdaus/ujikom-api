@extends('layouts.app')

@section('title', 'Pemantauan Pengembalian - PinjamIn Petugas')
@section('header-title', 'Pemantauan & Proses Pengembalian Alat')

@section('content')

    <!-- Flash Message -->
    @if(session('success'))
        <div class="mb-5 bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Container Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header Table & Search Toolbar -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Peminjaman Aktif (Belum Kembali)</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pantau batas waktu pengembalian dan proses pengembalian alat fisik.</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('petugas.pengembalian.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-64 md:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                        class="w-full pl-4 pr-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
                <button type="submit" class="bg-[#0F172A] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table List -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">PEMINJAM</th>
                        <th class="py-4 px-6">TGL PINJAM</th>
                        <th class="py-4 px-6">RENCANA KEMBALI</th>
                        <th class="py-4 px-6 text-center">STATUS</th>
                        <th class="py-4 px-6">DETAIL ALAT</th>
                        <th class="py-4 px-6 w-80 text-center">AKSI PENGEMBALIAN</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($peminjamans as $peminjaman)
                        <tr class="hover:bg-gray-50/80 transition align-top">
                            <!-- Peminjam -->
                            <td class="py-4 px-6 font-bold text-gray-900 whitespace-nowrap">
                                {{ $peminjaman->user->name ?? 'User' }}
                            </td>

                            <!-- Tanggal Pinjam -->
                            <td class="py-4 px-6 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                <div>{{ $peminjaman->tgl_pinjam }}</div>
                                <div class="text-[10px] text-gray-400">00:00:00</div>
                            </td>

                            <!-- Rencana Kembali -->
                            <td class="py-4 px-6 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                <div>{{ $peminjaman->tgl_kembali_plan }}</div>
                                <div class="text-[10px] text-gray-400">00:00:00</div>
                            </td>

                            <!-- Badge Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold inline-block bg-blue-100 text-blue-700">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
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

                            <!-- Inline Form Proses Pengembalian -->
                            <td class="py-4 px-6 bg-gray-50/30">
                                <form action="{{ route('petugas.pengembalian.proses', $peminjaman->id) }}" method="POST" class="space-y-3 bg-white p-3.5 rounded-xl border border-gray-200/80 shadow-sm">
                                    @csrf

                                    <!-- Select Kondisi -->
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Kondisi Kembali</label>
                                        <select name="kondisi_kembali" required
                                                class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                            <option value="Baik / Normal">Baik / Normal</option>
                                            <option value="Rusak Sedang">Rusak Sedang</option>
                                            <option value="Rusak Berat / Hilang">Rusak Berat / Hilang</option>
                                        </select>
                                    </div>

                                    <!-- Input Denda -->
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Denda (Rp)</label>
                                        <input type="number" name="denda" value="0" min="0" required
                                               class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono">
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition">
                                        Proses Pengembalian
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-xs">Tidak ada peminjaman aktif yang menunggu pengembalian.</td>
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