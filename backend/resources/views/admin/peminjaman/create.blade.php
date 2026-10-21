@extends('layouts.app')

@section('title', 'Tambah Peminjaman - PinjamIn Admin')
@section('header-title', 'Form Tambah Transaksi Peminjaman')

@section('content')

    <div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8">
        
        <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Peminjam -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">PILIH PEMINJAM (USER)</label>
                <select name="user_id" required
                        class="w-full px-4 py-2.5 bg-gray-50/80 border @error('user_id') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tanggal Pinjam -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">TANGGAL PINJAM</label>
                    <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50/80 border @error('tgl_pinjam') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    @error('tgl_pinjam')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rencana Tanggal Kembali -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">RENCANA TANGGAL KEMBALI</label>
                    <input type="date" name="tgl_kembali_plan" value="{{ old('tgl_kembali_plan', now()->addDays(3)->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50/80 border @error('tgl_kembali_plan') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    @error('tgl_kembali_plan')
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Multi-Item Alat Section -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-2">DAFTAR ALAT YANG DIPINJAM</label>
                
                <div id="alat-container" class="space-y-3">
                    <!-- Dynamic Row Item Pertama -->
                    <div class="flex items-center gap-2 alat-row">
                        <select name="alat_id[]" required
                                class="flex-1 px-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="">-- Pilih Alat --</option>
                            @foreach($alats as $alat)
                                <option value="{{ $alat->id }}">
                                    {{ $alat->nama_alat }} (Tersedia Stok: {{ $alat->stok }})
                                </option>
                            @endforeach
                        </select>

                        <input type="number" name="jumlah[]" value="1" min="1" required placeholder="Jumlah"
                               class="w-20 px-3 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-center text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">

                        <button type="button" onclick="removeRow(this)"
                                class="p-2.5 bg-rose-50 hover:bg-rose-100 text-rose-500 rounded-xl transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah Baris Alat Lain -->
                <button type="button" onclick="addRow()" 
                        class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                    <span class="text-sm">+</span> Tambah Alat Lain
                </button>
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end gap-2.5 pt-4">
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition">
                    Simpan Peminjaman
                </button>
            </div>

        </form>

    </div>

    <!-- JavaScript Dynamic Rows -->
    <script>
        function addRow() {
            const container = document.getElementById('alat-container');
            const firstRow = container.querySelector('.alat-row');
            const newRow = firstRow.cloneNode(true);
            
            // Reset pilihan select dan input jumlah
            newRow.querySelector('select').value = '';
            newRow.querySelector('input[type="number"]').value = '1';
            
            container.appendChild(newRow);
        }

        function removeRow(btn) {
            const container = document.getElementById('alat-container');
            if (container.querySelectorAll('.alat-row').length > 1) {
                btn.closest('.alat-row').remove();
            } else {
                alert('Minimal harus memilih 1 alat.');
            }
        }
    </script>

@endsection