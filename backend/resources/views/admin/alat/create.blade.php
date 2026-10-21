@extends('layouts.app')

@section('title', 'Tambah Alat - PinjamIn Admin')
@section('header-title', 'Tambah Alat Baru')

@section('content')

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8">
        
        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Nama Alat -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">NAMA ALAT</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required autofocus
                       placeholder="Contoh: Router Mikrotik RB941"
                       class="w-full px-4 py-2.5 bg-gray-50/80 border @error('nama_alat') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                @error('nama_alat')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">KATEGORI</label>
                <select name="kategori_id" required
                        class="w-full px-4 py-2.5 bg-gray-50/80 border @error('kategori_id') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Stok & Status Kondisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Stok -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">STOK</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required
                           class="w-full px-4 py-2.5 bg-gray-50/80 border @error('stok') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    @error('stok')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Kondisi -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">STATUS KONDISI</label>
                    <select name="status_kondisi" required
                            class="w-full px-4 py-2.5 bg-gray-50/80 border @error('status_kondisi') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="Baik" {{ old('status_kondisi') === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('status_kondisi') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('status_kondisi') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('status_kondisi')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">DESKRIPSI <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                          placeholder="Keterangan tambahan tentang alat..."
                          class="w-full px-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Gambar Alat -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">GAMBAR ALAT <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="file" name="gambar" accept="image/*"
                       class="block w-full text-xs text-gray-500 bg-gray-50/80 border border-gray-200 rounded-xl cursor-pointer file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition">
                @error('gambar')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end gap-2.5 pt-4">
                <a href="{{ route('admin.alat.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition">
                    Simpan
                </button>
            </div>

        </form>

    </div>

@endsection