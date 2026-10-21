@extends('layouts.app')

@section('title', 'Verifikasi Pengembalian - PinjamIn Admin')
@section('header-title', 'Form Verifikasi Pengembalian Alat')

@section('content')

    <div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8">
        
        <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section: Detail Peminjam & Barang -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4">Detail Peminjam & Barang</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mb-4">
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">NAMA PEMINJAM</span>
                        <h4 class="font-bold text-gray-900 text-sm mt-0.5">{{ $peminjaman->user->name ?? 'User' }}</h4>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">TANGGAL PINJAM</span>
                        <h4 class="font-mono text-gray-700 text-xs mt-0.5">{{ $peminjaman->tgl_pinjam }} 00:00:00</h4>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">RENCANA KEMBALI</span>
                        <h4 class="font-mono text-gray-700 text-xs mt-0.5">{{ $peminjaman->tgl_kembali_plan }} 00:00:00</h4>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">TANGGAL PENGEMBALIAN</span>
                        <h4 class="font-mono text-emerald-600 font-bold text-xs mt-0.5">{{ $tglKembaliReal->format('Y-m-d') }}</h4>
                    </div>
                </div>

                <!-- Box Daftar Barang Dipinjam -->
                <div class="bg-gray-50/80 border border-gray-100 rounded-xl p-4 space-y-2">
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">DAFTAR BARANG DIPINJAM:</span>
                    <ul class="space-y-1.5 text-xs text-gray-800">
                        @foreach($peminjaman->detailPinjam as $detail)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span>{{ $detail->alat->nama_alat ?? 'Alat' }} - <strong class="font-bold">{{ $detail->jumlah }} Unit</strong></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Box Perhitungan Keterlambatan Otomatis -->
            <div class="bg-amber-50/80 border border-amber-200/80 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">PERHITUNGAN KETERLAMBATAN (OTOMATIS)</h4>
                    <p class="text-[11px] text-amber-800 mt-1">
                        Tarif denda keterlambatan: <strong class="text-amber-950">Rp 2.500 / hari</strong><br>
                        Total Hari Terlambat: <strong class="text-amber-950">{{ $hariTerlambat }} Hari</strong>
                    </p>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-bold text-amber-800 uppercase tracking-wider">Denda Terlambat:</span>
                    <span class="text-lg font-bold text-amber-900 font-mono">Rp {{ number_format($dendaTerlambat, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Hidden Input Denda Terlambat -->
            <input type="hidden" id="denda_keterlambatan" name="denda_keterlambatan" value="{{ $dendaTerlambat }}">

            <!-- Kondisi Alat Saat Dikembalikan -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">KONDISI ALAT SAAT DIKEMBALIKAN</label>
                <select name="kondisi_kembali" id="kondisi_kembali" required onchange="calculateTotalDenda()"
                        class="w-full px-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    <option value="Baik / Normal (Tanpa Denda Kerusakan)">Baik / Normal (Tanpa Denda Kerusakan)</option>
                    <option value="Rusak Sedang">Rusak Sedang</option>
                    <option value="Rusak Berat / Hilang">Rusak Berat / Hilang</option>
                </select>
            </div>

            <!-- Nominal Denda Kerusakan Fisik -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">NOMINAL DENDA KERUSAKAN FISIK (RP)</label>
                <input type="number" name="denda_kerusakan" id="denda_kerusakan" value="0" min="0" oninput="calculateTotalDenda()"
                       class="w-full px-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition font-mono">
            </div>

            <!-- Box Grand Total Denda (Hitam Solid) -->
            <div class="bg-[#0F172A] text-white p-5 rounded-2xl shadow-lg">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL DENDA YANG HARUS DIBAYAR</span>
                <span id="display_total_denda" class="text-2xl font-bold text-emerald-400 font-mono mt-1 block">
                    Rp {{ number_format($dendaTerlambat, 0, ',', '.') }}
                </span>
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end gap-2.5 pt-2">
                <a href="{{ route('admin.pengembalian.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition">
                    Konfirmasi Selesai Pengembalian
                </button>
            </div>

        </form>

    </div>

    <!-- Script Hitung Total Denda Realtime -->
    <script>
        function calculateTotalDenda() {
            const dendaTerlambat = parseFloat(document.getElementById('denda_keterlambatan').value) || 0;
            const dendaKerusakanInput = document.getElementById('denda_kerusakan');
            const dendaKerusakan = parseFloat(dendaKerusakanInput.value) || 0;
            
            const total = dendaTerlambat + dendaKerusakan;
            
            // Format Rupiah
            document.getElementById('display_total_denda').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>

@endsection