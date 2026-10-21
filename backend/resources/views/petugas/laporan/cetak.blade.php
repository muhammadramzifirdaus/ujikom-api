<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Peminjaman Alat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2, .header p { margin: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; float: right; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; background: #f8f9fa; padding: 10px; border-radius: 5px; text-align: right;">
        <button onclick="window.print()" style="background: #059669; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak / Save as PDF
        </button>
        <button onclick="window.close()" style="background: #4b5563; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>LAPORAN PEMINJAMAN & PENGEMBALIAN ALAT</h2>
        <p>Sistem Informasi Manajemen Inventaris Alat</p>
        @if(request('dari_tanggal') && request('sampai_tanggal'))
            <p style="font-size: 11px;">Periode: {{ request('dari_tanggal') }} s/d {{ request('sampai_tanggal') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Peminjam</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Rencana Kembali</th>
                <th width="10%">Status</th>
                <th width="25%">Detail Alat</th>
                <th width="10%">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? 'User Dihapus' }}</td>
                    <td>{{ $item->tgl_pinjam }}</td>
                    <td>{{ $item->tgl_kembali_plan }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach($item->detailPinjam as $detail)
                                <li>{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} ({{ $detail->jumlah }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>Rp {{ number_format($item->pengembalian->denda ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Baleendah, {{ date('d F Y') }}</p>
        <p>Petugas Pengelola,</p>
        <br><br><br>
        <p><strong>{{ auth()->user()->name }}</strong></p>
    </div>

</body>
</html>