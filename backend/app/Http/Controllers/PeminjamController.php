<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PeminjamController extends Controller
{
    // ==========================================
    // 1. KATALOG ALAT & FORM AJUKAN
    // ==========================================
    public function katalogAlat()
    {
        // Ambil alat yang memiliki stok lebih dari 0
        $alats = Alat::with('kategori')->where('stok', '>', 0)->latest()->get();
        
        // Ambil semua kategori untuk filter tombol di Blade
        $kategoris = Kategori::all();

        return view('peminjam.katalog', compact('alats', 'kategoris'));
    }

    // ==========================================
    // 2. MEMPROSES PENGAJUAN PEMINJAMAN (MULTI-ITEM)
    // ==========================================
    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'tgl_pinjam'       => 'nullable|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:today',
            'keperluan'        => 'nullable|string',
            'alat_id'          => 'required|array|min:1',
            'alat_id.*'        => 'required|exists:alat,id',
            'jumlah'           => 'required|array|min:1',
            'jumlah.*'         => 'required|integer|min:1',
        ]);

        // Cek jika ada alat yang dipilih ganda/duplikat
        if (count($request->alat_id) !== count(array_unique($request->alat_id))) {
            return redirect()->back()->withInput()->with('error', 'Terdapat alat yang dipilih lebih dari satu kali. Silakan gabungkan jumlahnya.');
        }

        DB::beginTransaction();
        try {
            // Validasi ketersediaan stok setiap alat
            foreach ($request->alat_id as $index => $alatId) {
                $jumlahPinjam = $request->jumlah[$index];
                $alat = Alat::findOrFail($alatId);

                if ($alat->stok < $jumlahPinjam) {
                    throw new Exception("Stok untuk alat '{$alat->nama_alat}' tidak mencukupi (Tersedia: {$alat->stok}).");
                }
            }

            // 1. Buat header peminjaman
            $peminjaman = Peminjaman::create([
                'user_id'          => auth()->id(),
                'tgl_pinjam'       => $request->tgl_pinjam ?? now()->toDateString(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'keperluan'        => $request->keperluan,
                'status'           => 'diajukan',
            ]);

            // 2. Masukkan semua alat yang dipilih ke detail_pinjam
            foreach ($request->alat_id as $index => $alatId) {
                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $alatId,
                    'jumlah'        => $request->jumlah[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu verifikasi petugas.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 3. RIWAYAT PEMINJAMAN USER YANG LOGIN
    // ==========================================
    public function riwayatPeminjaman()
    {
        $userId = auth()->id();

        $peminjamans = Peminjaman::with(['detailPinjam.alat', 'pengembalian'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        // Perhitungan Statistik
        $statAktif = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['diajukan', 'dipinjam'])
            ->count();

        $statSelesai = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['dikembalikan', 'selesai'])
            ->count();

        $statTotalDenda = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->where('peminjaman.user_id', $userId)
            ->sum('pengembalian.denda');

        return view('peminjam.riwayat', compact('peminjamans', 'statAktif', 'statSelesai', 'statTotalDenda'));
    }
}