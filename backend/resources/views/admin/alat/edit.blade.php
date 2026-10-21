@extends('layouts.app')

@section('title', 'Edit Alat - PinjamIn Admin')
@section('header-title', 'Edit Data Alat')

@section('content')

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8">
        
        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Alat -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">NAMA ALAT</label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50/80 border @error('nama_alat') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                @error('nama_alat')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">KATEGORI</label>
                <select name="kategori_id" required
                        class="w-full px-4 py-2.5 bg-gray-50/80 border @error('kategori_id') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    <input type="number" name="stok" value="{{ old('stok', $alat->stok) }}" min="0" required
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
                        <option value="Baik" {{ old('status_kondisi', $alat->status_kondisi) === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('status_kondisi', $alat->status_kondisi) === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('status_kondisi', $alat->status_kondisi) === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('status_kondisi')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi Opsional -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">DESKRIPSI <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
            </div>

            <!-- Gambar Alat Opsional -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">GAMBAR ALAT <span class="text-gray-400 font-normal">(opsional)</span></label>
                
                @if($alat->gambar && file_exists(public_path($alat->gambar)))
                    <div class="mb-2 flex items-center gap-3 p-2 bg-gray-50 rounded-xl border border-gray-200 w-fit">
                        <img src="{{ asset($alat->gambar) }}" alt="Preview" class="w-10 h-10 object-cover rounded-lg">
                        <span class="text-[11px] text-gray-500 font-medium">Gambar saat ini</span>
                    </div>
                @endif

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
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

@endsection