<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Laporan Inventori</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #2563eb; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .text-right { text-align: right; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .footer { text-align: right; margin-top: 40px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 5px;">Print / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>InvenTrack Logistik</h1>
        <p>Laporan Rekapitulasi Transaksi Barang</p>
        <p>Periode: {{ \Carbon\Carbon::create((int)$tahun, (int)$bulan, 1)->translatedFormat('F') }} {{ (int)$tahun }}</p>
    </div>

    <div class="title">I. Barang Masuk</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Deskripsi Barang</th>
                <th width="15%">Kategori</th>
                <th width="15%" class="text-right">Jumlah</th>
                <th width="10%" class="text-right">Harga</th>
                <th width="10%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangMasuks as $index => $bm)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($bm->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $bm->barang->nama_barang }}</td>
                <td>{{ $bm->barang->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">{{ $bm->jumlah }} {{ $bm->barang->satuan }}</td>
                <td class="text-right">Rp {{ number_format($bm->barang->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($bm->jumlah * $bm->barang->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center;">Tidak ada transaksi barang masuk</td></tr>
            @endforelse
            <tr>
                <th colspan="6" class="text-right">Total Nilai Barang Masuk</th>
                <th class="text-right">Rp {{ number_format($barangMasuks->sum(fn($bm) => $bm->jumlah * $bm->barang->harga_satuan), 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <div class="title">II. Barang Keluar</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Deskripsi Barang</th>
                <th width="15%">Kategori</th>
                <th width="15%" class="text-right">Jumlah</th>
                <th width="10%" class="text-right">Harga</th>
                <th width="10%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangKeluars as $index => $bk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($bk->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $bk->barang->nama_barang }}</td>
                <td>{{ $bk->barang->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">{{ $bk->jumlah }} {{ $bk->barang->satuan }}</td>
                <td class="text-right">Rp {{ number_format($bk->barang->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($bk->jumlah * $bk->barang->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center;">Tidak ada transaksi barang keluar</td></tr>
            @endforelse
            <tr>
                <th colspan="6" class="text-right">Total Nilai Barang Keluar</th>
                <th class="text-right">Rp {{ number_format($barangKeluars->sum(fn($bk) => $bk->jumlah * $bk->barang->harga_satuan), 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
        <br><br><br>
        <p>_______________________</p>
        <p>Bagian Administrasi / Penanggung Jawab</p>
    </div>
</body>
</html>
