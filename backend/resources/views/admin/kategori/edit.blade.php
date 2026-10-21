@extends('layouts.app')

@section('title', 'Edit Kategori - PinjamIn Admin')
@section('header-title', 'Edit Kategori Alat')

@section('content')

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8">
        
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Kategori -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">NAMA KATEGORI</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required autofocus
                       class="w-full px-4 py-2.5 bg-gray-50/80 border @error('nama_kategori') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                @error('nama_kategori')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end gap-2.5 pt-4">
                <a href="{{ route('admin.kategori.index') }}" 
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