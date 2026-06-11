@extends('layouts.app')

@section('header', 'Buku Kas & Kelola Saldo')

@section('content')
<div x-data="{ addModal: false, deleteModal: false, deleteUrl: '', deleteName: '' }">
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Running Cash Balance -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-500 rounded-xl shadow-md p-6 text-white flex flex-col hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-medium text-emerald-100">Saldo Kas Saat Ini</h3>
                <div class="p-2 bg-emerald-500/30 rounded-lg text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold">Rp {{ number_format($runningSaldo, 0, ',', '.') }}</div>
            <p class="text-emerald-100 text-xs mt-2">Dihitung otomatis dari transaksi & top-up</p>
        </div>

        <!-- Total Top Up -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="text-slate-500 font-medium">Total Input Modal</h3>
            </div>
            <div class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalTopup, 0, ',', '.') }}</div>
        </div>

        <!-- Total Pembelian (Barang Masuk) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="text-slate-500 font-medium">Pengeluaran Barang</h3>
            </div>
            <div class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
        </div>

        <!-- Total Penjualan (Barang Keluar) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-slate-500 font-medium">Penerimaan Barang</h3>
            </div>
            <div class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Action Section -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-slate-800">Riwayat Buku Kas & Log Aliran Dana</h2>
        <button @click="addModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Input / Top Up Saldo
        </button>
    </div>

    <!-- Ledger Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Keterangan Transaksi</th>
                        <th class="px-6 py-4">Aliran Dana</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($ledger as $key => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $key + 1 }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $item['keterangan'] }}</td>
                        <td class="px-6 py-4">
                            @if($item['tipe'] == 'masuk_topup')
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 uppercase">Top Up Modal</span>
                            @elseif($item['tipe'] == 'masuk_barang')
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 uppercase">Penerimaan (Barang Keluar)</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700 uppercase">Pengeluaran (Barang Masuk)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-sm">
                            @if($item['tipe'] == 'masuk_topup' || $item['tipe'] == 'masuk_barang')
                                <span class="text-emerald-600">+ Rp {{ number_format($item['nominal'], 0, ',', '.') }}</span>
                            @else
                                <span class="text-rose-600">- Rp {{ number_format($item['nominal'], 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item['is_deletable'])
                                <button @click="deleteUrl = '{{ route('saldo.destroy', $item['id']) }}'; deleteName = 'Top Up Rp ' + '{{ number_format($item['nominal'], 0, ',', '.') }}'; deleteModal = true" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded transition inline-flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Hapus
                                </button>
                            @else
                                <span class="text-xs text-slate-400 italic">Transaksi Otomatis</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada aktivitas buku kas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Input/Topup Saldo -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="addModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="addModal = false"></div>
        <div x-show="addModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-800">Top Up / Input Saldo Kas</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <form action="{{ route('saldo.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nominal Modal Masuk (Rp)</label>
                        <input type="number" name="nominal" min="1" required placeholder="0" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold text-emerald-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Deposit Awal Tahun, Tambahan Kas Operasional" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Topup -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="deleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteModal = false"></div>
        <div x-show="deleteModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4">
                    <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus Top Up Saldo</h3>
                <p class="text-slate-500 text-sm mb-8">Apakah Anda yakin ingin membatalkan & menghapus transaksi <span class="font-semibold text-slate-800" x-text="deleteName"></span>? Saldo kas akan berkurang kembali.</p>
                
                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" @click="deleteModal = false" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 text-white rounded-lg text-sm font-medium hover:bg-rose-700 transition shadow-lg shadow-rose-200">Ya, Hapus!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
