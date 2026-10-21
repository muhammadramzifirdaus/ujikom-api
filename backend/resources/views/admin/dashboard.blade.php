@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')

    <!-- Alert Selamat Datang Emerald Banner -->
    <div class="bg-emerald-100/80 border border-emerald-200 text-emerald-950 p-4 rounded-2xl flex items-center gap-3.5 shadow-sm">
        <div class="w-9 h-9 rounded-full bg-emerald-200 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 10 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="text-xs">
            Selamat datang, <strong class="font-bold text-gray-900">{{ auth()->user()->name }}</strong>! Anda login sebagai hak akses
            <span class="bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider ml-1">
                {{ auth()->user()->role }}
            </span>.
        </div>
    </div>

    <!-- Tabel Log Aktivitas Terbaru Card -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80">
        
        <!-- Header Card Log -->
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Log Aktivitas Terbaru</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Catatan riwayat tindakan dan perubahan data dalam sistem</p>
            </div>
            <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-3 py-1 rounded-xl">
                {{ count($logs) }} Aktivitas Terbaru
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-5">WAKTU</th>
                        <th class="py-3.5 px-5">USER</th>
                        <th class="py-3.5 px-5">AKTIVITAS</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 text-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3.5 px-5 text-gray-400 font-mono text-[11px]">
                                {{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 font-bold text-gray-900">
                                {{ $log->user->name ?? 'Sistem' }}
                            </td>
                            <td class="py-3.5 px-5">
                                {!! $log->aktivitas !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-400 text-xs">Belum ada log aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection