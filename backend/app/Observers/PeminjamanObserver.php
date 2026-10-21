<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PeminjamanObserver
{
    private function catatLog(string $pesan, ?int $userId = null): void
    {
        LogAktivitas::create([
            'user_id'   => Auth::id() ?? $userId,
            'aktivitas' => $pesan,
        ]);
    }

    public function created(Peminjaman $peminjaman): void
    {
        $this->catatLog(
            "Menambahkan data peminjaman baru ID: {$peminjaman->id} dengan status: '{$peminjaman->status}'",
            $peminjaman->user_id
        );
    }

    public function updated(Peminjaman $peminjaman): void
    {
        $changes = [];
        foreach ($peminjaman->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $peminjaman->getOriginal($key);
                $changes[] = "kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detailPerubahan = !empty($changes) ? implode(', ', $changes) : 'memperbarui data';

        $this->catatLog(
            "Memperbarui peminjaman ID: {$peminjaman->id}: ({$detailPerubahan})",
            $peminjaman->user_id
        );
    }

    public function deleted(Peminjaman $peminjaman): void
    {
        $this->catatLog(
            "Menghapus data peminjaman ID: {$peminjaman->id}",
            $peminjaman->user_id
        );
    }
}