<?php

namespace App\Observers;

use App\Models\Pengembalian;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PengembalianObserver
{
    private function catatLog(string $pesan, ?int $userId = null): void
    {
        LogAktivitas::create([
            'user_id'   => Auth::id() ?? $userId,
            'aktivitas' => $pesan,
        ]);
    }

    public function created(Pengembalian $pengembalian): void
    {
        $dendaFormat = number_format($pengembalian->denda ?? 0, 0, ',', '.');
        $this->catatLog(
            "Memproses pengembalian alat untuk Peminjaman ID: {$pengembalian->peminjaman_id} dengan kondisi '{$pengembalian->kondisi_kembali}' dan denda Rp {$dendaFormat}",
            $pengembalian->petugas_id
        );
    }

    public function updated(Pengembalian $pengembalian): void
    {
        $changes = [];
        foreach ($pengembalian->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $pengembalian->getOriginal($key);
                $changes[] = "kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detailPerubahan = !empty($changes) ? implode(', ', $changes) : 'memperbarui data';

        $this->catatLog(
            "Memperbarui pengembalian ID: {$pengembalian->id}: ({$detailPerubahan})",
            $pengembalian->petugas_id
        );
    }

    public function deleted(Pengembalian $pengembalian): void
    {
        $this->catatLog(
            "Menghapus data pengembalian ID: {$pengembalian->id}",
            $pengembalian->petugas_id
        );
    }
}