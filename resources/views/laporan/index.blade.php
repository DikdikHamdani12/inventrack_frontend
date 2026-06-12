@extends('layouts.app')

@section('header', 'Laporan Inventori')

@section('content')
<div class="mb-6 bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between md:items-end gap-4">
    <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col sm:flex-row gap-4 flex-1">
        <div class="flex-1 w-full max-w-xs">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
            <select name="bulan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @for($m=1; $m<=12; $m++)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                </option>
                @endfor
            </select>
        </div>
        <div class="flex-1 w-full max-w-xs">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
            <select name="tahun" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @for($y=date('Y'); $y>=date('Y')-3; $y--)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2 rounded-lg text-sm font-medium transition w-full sm:w-auto h-[38px]">
                Filter Data
            </button>
        </div>
    </form>

    <div class="flex items-end h-[38px] md:mb-0">
        <a href="{{ route('laporan.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition flex items-center shadow-sm w-full md:w-auto justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print / Export PDF
        </a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Tabel Masuk -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col h-[500px]">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 flex items-center">
                <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                Barang Masuk
            </h3>
            <span class="text-xs font-semibold px-2 py-1 bg-blue-100 text-blue-700 rounded-full">Total: Rp {{ number_format($barangMasuks->sum(fn($bm) => $bm->jumlah * $bm->barang->harga_satuan), 0, ',', '.') }}</span>
        </div>
        <div class="flex-1 overflow-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white text-slate-500 font-semibold border-b border-slate-200 sticky top-0 shadow-sm z-10">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($barangMasuks as $bm)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($bm->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $bm->barang->nama_barang }} <span class="text-slate-400 text-xs">({{ $bm->barang->kategori->nama_kategori ?? '-' }})</span></td>
                        <td class="px-4 py-3 text-right font-medium text-blue-600">+{{ $bm->jumlah }} {{ $bm->barang->satuan }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">Rp {{ number_format($bm->barang->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($bm->jumlah * $bm->barang->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Keluar -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col h-[500px]">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 flex items-center">
                <div class="w-2 h-2 rounded-full bg-rose-500 mr-2"></div>
                Barang Keluar
            </h3>
            <span class="text-xs font-semibold px-2 py-1 bg-rose-100 text-rose-700 rounded-full">Total: Rp {{ number_format($barangKeluars->sum(fn($bk) => $bk->jumlah * $bk->barang->harga_satuan), 0, ',', '.') }}</span>
        </div>
        <div class="flex-1 overflow-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white text-slate-500 font-semibold border-b border-slate-200 sticky top-0 shadow-sm z-10">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($barangKeluars as $bk)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($bk->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $bk->barang->nama_barang }} <span class="text-slate-400 text-xs">({{ $bk->barang->kategori->nama_kategori ?? '-' }})</span></td>
                        <td class="px-4 py-3 text-right font-medium text-rose-600">-{{ $bk->jumlah }} {{ $bk->barang->satuan }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">Rp {{ number_format($bk->barang->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($bk->jumlah * $bk->barang->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
