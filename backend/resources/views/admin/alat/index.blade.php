@extends('layouts.app')

@section('title', 'Kelola Alat - PinjamIn Admin')
@section('header-title', 'Manajemen Data Alat')

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
                <h3 class="text-base font-bold text-gray-900">Daftar Alat Laboratorium</h3>
                <p class="text-xs text-gray-400 mt-0.5">Kelola inventarisasi, ketersediaan stok, dan kondisi peralatan</p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Search Form -->
                <form action="{{ route('admin.alat.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative w-64 md:w-72">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat, kategori..."
                            class="w-full pl-9 pr-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>
                    <button type="submit" class="bg-[#0F172A] hover:bg-slate-800 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                        Cari
                    </button>
                </form>

                <!-- Tombol Tambah Alat -->
                <a href="{{ route('admin.alat.create') }}" 
                   class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5 whitespace-nowrap">
                    <span class="text-sm font-bold">+</span> Tambah Alat
                </a>
            </div>
        </div>

        <!-- Tabel Alat -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 w-24">GAMBAR</th>
                        <th class="py-4 px-6">NAMA ALAT</th>
                        <th class="py-4 px-6">KATEGORI</th>
                        <th class="py-4 px-6 w-20 text-center">STOK</th>
                        <th class="py-4 px-6 w-28 text-center">KONDISI</th>
                        <th class="py-4 px-6 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($alats as $item)
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Thumbnail Gambar -->
                            <td class="py-3 px-6">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($item->gambar && file_exists(public_path($item->gambar)))
                                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_alat }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 px-1 text-center truncate">
                                            {{ Str::limit($item->nama_alat, 4, '') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Nama Alat -->
                            <td class="py-4 px-6 font-bold text-gray-900">{{ $item->nama_alat }}</td>

                            <!-- Kategori -->
                            <td class="py-4 px-6 text-gray-500 font-medium">
                                {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                            </td>

                            <!-- Stok -->
                            <td class="py-4 px-6 font-bold text-gray-900 text-center">{{ $item->stok }}</td>

                            <!-- Kondisi -->
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold inline-block capitalize
                                    @if(strtolower($item->status_kondisi) === 'baik') bg-emerald-100 text-emerald-700
                                    @elseif(strtolower($item->status_kondisi) === 'rusak ringan') bg-amber-100 text-amber-700
                                    @else bg-rose-100 text-rose-700 @endif">
                                    {{ ucfirst($item->status_kondisi) }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('admin.alat.edit', $item->id) }}" 
                                       class="bg-amber-50 hover:bg-amber-100 text-amber-600 text-[11px] font-bold px-3.5 py-1.5 rounded-lg transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.alat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus alat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold px-3.5 py-1.5 rounded-lg transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-xs">Belum ada data alat laboratorium.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($alats, 'links'))
            <div class="p-4 border-t border-gray-100">
                {{ $alats->links() }}
            </div>
        @endif

    </div>

@endsection