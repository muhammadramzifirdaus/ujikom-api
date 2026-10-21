<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AlatObserver
{
    private function catatLog(string $pesan): void
    {
        // Ambil ID user yang login. Jika NULL (misal dari Seeder), gunakan ID user admin/pertama
        $userId = Auth::id() ?? User::where('role', 'admin')->value('id') ?? User::value('id');

        // Hanya catat jika ada ID user yang valid
        if ($userId) {
            LogAktivitas::create([
                'user_id'   => $userId,
                'aktivitas' => $pesan,
            ]);
        }
    }

    public function created(Alat $alat): void
    {
        $this->catatLog("Menambahkan data alat baru: '{$alat->nama_alat}' (Stok: {$alat->stok}, Kondisi: {$alat->status_kondisi})");
    }

    public function updated(Alat $alat): void
    {
        $changes = [];
        foreach ($alat->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $alat->getOriginal($key);
                $changes[] = "kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detailPerubahan = !empty($changes) ? implode(', ', $changes) : 'memperbarui data alat';

        $this->catatLog("Memperbarui alat '{$alat->nama_alat}': ({$detailPerubahan})");
    }

    public function deleted(Alat $alat): void
    {
        $this->catatLog("Menghapus data alat: '{$alat->nama_alat}'");
    }
}