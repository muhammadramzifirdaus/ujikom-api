@extends('layouts.app')

@section('title', 'Katalog Alat - PinjamIn')
@section('header-title', 'Katalog Alat Tersedia')

@section('content')

    <!-- Flash Messages -->
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

    <!-- Include Alpine.js untuk Keranjang Interactive Realtime -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div x-data="peminjamanCart()" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- KOLOM KIRI & TENGAH: KATALOG ALAT (2 Kolom Layout) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Search Bar & Filter Kategori Badges -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama alat, spesifikasi..."
                           class="w-full pl-9 pr-4 py-2 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Category Pill Filters -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs">
                    <button @click="selectedCategory = 'all'" 
                            :class="selectedCategory === 'all' ? 'bg-[#0F172A] text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-3.5 py-1.5 rounded-xl transition whitespace-nowrap">
                        Semua Kategori
                    </button>
                    @foreach($kategoris as $kat)
                        <button @click="selectedCategory = '{{ $kat->id }}'" 
                                :class="selectedCategory === '{{ $kat->id }}' ? 'bg-[#0F172A] text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="px-3.5 py-1.5 rounded-xl transition whitespace-nowrap">
                            {{ $kat->nama_kategori }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grid Card Alat (2 Kolom Card) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($alats as $alat)
                    <div x-show="filterAlat('{{ $alat->nama_alat }}', '{{ $alat->kategori_id }}')" 
                         class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-200">
                        
                        <div>
                            <!-- Header Image & Kategori Tag -->
                            <div class="relative h-44 bg-gray-100 border-b border-gray-100 p-3 flex items-center justify-center overflow-hidden">
                                <span class="absolute top-3 left-3 bg-purple-100 text-purple-700 font-bold text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider z-10">
                                    {{ $alat->kategori->nama_kategori ?? 'UMUM' }}
                                </span>
                                
                                @if($alat->gambar && file_exists(public_path($alat->gambar)))
                                    <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <div class="text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[10px] font-semibold mt-1 block">Tidak ada foto</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Body Detail Alat -->
                            <div class="p-5">
                                <h4 class="font-bold text-gray-900 text-sm leading-snug">{{ $alat->nama_alat }}</h4>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $alat->deskripsi ?? 'Alat laboratorium standar praktik jurusan.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer Stok & Button Tambah -->
                        <div class="px-5 pb-5 pt-1 flex items-center justify-between border-t border-gray-50 mt-auto">
                            <div>
                                <span class="block text-[10px] text-gray-400 font-medium">Tersedia</span>
                                <span class="text-sm font-bold text-emerald-600 font-mono">{{ $alat->stok }} Unit</span>
                            </div>

                            <button @click="addToCart({{ $alat->id }}, '{{ addslashes($alat->nama_alat) }}', {{ $alat->stok }})" 
                                    :disabled="{{ $alat->stok }} <= 0"
                                    class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-200 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-1">
                                <span>+</span> Tambah
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="col-span-2 bg-white rounded-2xl p-8 text-center text-gray-400 text-xs">
                        Belum ada alat laboratorium yang dapat dipinjam.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- KOLOM KANAN: FORM AJUKAN PEMINJAMAN (KERANJANG) -->
        <div class="lg:col-span-1 sticky top-24">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 space-y-5">
                
                <!-- Header Cart -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm">Form Ajukan Peminjaman</h3>
                    </div>
                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded-lg" x-text="cart.length + ' Barang'">
                        0 Barang
                    </span>
                </div>

                <!-- Form Real Action -->
                <form action="{{ route('peminjam.ajukan') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Tanggal Pinjam -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">TANGGAL PINJAM</label>
                        <input type="date" name="tgl_pinjam" value="{{ now()->format('Y-m-d') }}" required
                               class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono transition">
                    </div>

                    <!-- Rencana Tanggal Kembali -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">RENCANA TANGGAL KEMBALI</label>
                        <input type="date" name="tgl_kembali_plan" value="{{ now()->addDays(3)->format('Y-m-d') }}" required
                               class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono transition">
                    </div>

                    <!-- Keperluan Peminjaman -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">KEPERLUAN PEMINJAMAN</label>
                        <textarea name="keperluan" rows="2" placeholder="Contoh: Praktikum Jaringan Komputer II" required
                                  class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"></textarea>
                    </div>

                    <!-- List Item Keranjang -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider">DAFTAR BARANG DIPILIH</label>
                            <button type="button" @click="cart = []" x-show="cart.length > 0" class="text-[10px] text-rose-500 font-semibold hover:underline">
                                Kosongkan
                            </button>
                        </div>

                        <!-- Card Empty State -->
                        <template x-if="cart.length === 0">
                            <div class="p-4 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center text-gray-400 text-[11px]">
                                Belum ada alat dipilih. Klik "+ Tambah" pada katalog.
                            </div>
                        </template>

                        <!-- Item Rows -->
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="flex items-center justify-between bg-gray-50/80 border border-gray-200/80 p-2.5 rounded-xl text-xs">
                                    <div class="overflow-hidden pr-2">
                                        <h5 class="font-bold text-gray-900 truncate" x-text="item.nama"></h5>
                                        <span class="text-[10px] text-gray-400">Jumlah dipinjam</span>
                                    </div>

                                    <!-- Hidden Inputs untuk Laravel Backend -->
                                    <input type="hidden" name="alat_id[]" :value="item.id">

                                    <div class="flex items-center gap-2 shrink-0">
                                        <input type="number" name="jumlah[]" x-model.number="item.qty" min="1" :max="item.maxStok"
                                               class="w-14 px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold text-center focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                        <button type="button" @click="removeFromCart(index)" class="text-rose-500 hover:text-rose-700 font-bold p-1">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="cart.length === 0"
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-200 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition duration-200 flex items-center justify-center gap-2 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Pengajuan Sekaligus
                    </button>

                </form>

            </div>
        </div>

    </div>

    <!-- Script Alpine JS Cart State Management -->
    <script>
        function peminjamanCart() {
            return {
                cart: [],
                searchQuery: '',
                selectedCategory: 'all',

                addToCart(id, nama, maxStok) {
                    const existing = this.cart.find(i => i.id === id);
                    if (existing) {
                        if (existing.qty < maxStok) existing.qty++;
                    } else {
                        this.cart.push({ id, nama, qty: 1, maxStok });
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                filterAlat(nama, kategoriId) {
                    const matchSearch = nama.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchCategory = this.selectedCategory === 'all' || this.selectedCategory == kategoriId;
                    return matchSearch && matchCategory;
                }
            }
        }
    </script>

@endsection