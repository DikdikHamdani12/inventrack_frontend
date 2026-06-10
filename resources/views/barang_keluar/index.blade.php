@extends('layouts.app')

@section('header', 'Transaksi Barang Keluar')

@section('content')
<div x-data="{ addModal: {{ $errors->any() ? 'true' : 'false' }}, deleteModal: false, deleteUrl: '', deleteName: '' }">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('barang-keluar.index') }}" class="w-full sm:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama barang..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </form>

        <!-- Tambah Button -->
        <button @click="addModal = true" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2 text-rose-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
            Barang Keluar
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal Keluar</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-right">Harga Satuan</th>
                        <th class="px-6 py-4 text-right">Total Nominal</th>
                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($barangKeluars as $key => $trx)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $barangKeluars->firstItem() + $key }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 font-medium">{{ $trx->barang->nama_barang }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] uppercase tracking-wider">{{ $trx->barang->kategori->nama_kategori ?? '-' }}</span></td>
                        <td class="px-6 py-4 text-center font-semibold text-rose-600">- {{ $trx->jumlah }} {{ $trx->barang->satuan }}</td>
                        <td class="px-6 py-4 text-right font-medium text-slate-600">Rp {{ number_format($trx->barang->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-rose-700">Rp {{ number_format($trx->jumlah * $trx->barang->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if(auth()->user()->role == 'admin')
                            <button @click="deleteUrl = '{{ route('barang-keluar.destroy', $trx->id) }}'; deleteName = '{{ $trx->barang->nama_barang }}'; deleteModal = true" class="text-rose-500 hover:text-white hover:bg-rose-500 border border-transparent hover:border-rose-600 px-3 py-1.5 rounded text-xs font-medium transition flex items-center m-auto">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Batalkan
                            </button>
                            @else
                            <span class="text-xs text-slate-400 italic">No access</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi barang keluar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($barangKeluars->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $barangKeluars->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

    <!-- Modal Form Barang Keluar -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="addModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="addModal = false"></div>
        <div x-show="addModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-800">Input Barang Keluar</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            
            <form action="{{ route('barang-keluar.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Keluar</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Barang</label>
                        <select name="barang_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none bg-white">
                            <option value="">-- Barang Tersedia (Stok > 0) --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }} (Tersedia: {{ $b->stok }} {{ $b->satuan }} - Rp {{ number_format($b->harga_satuan, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Keluar</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" required placeholder="0" class="w-full border @error('jumlah') border-red-500 @else border-slate-300 @enderror rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none font-bold text-rose-600">
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-lg text-sm font-medium hover:bg-rose-700 transition shadow-md">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Hapus Transaksi -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="deleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteModal = false"></div>
        <div x-show="deleteModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4">
                    <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Batalkan Transaksi</h3>
                <p class="text-slate-500 text-sm mb-8">Apakah Anda yakin ingin membatalkan transaksi pengeluaran barang <span class="font-semibold text-slate-800" x-text="deleteName"></span> ini? Stok akan otomatis dikembalikan ke gudang.</p>
                
                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" @click="deleteModal = false" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Kembali</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 text-white rounded-lg text-sm font-medium hover:bg-rose-700 transition shadow-lg shadow-rose-200">Ya, Batalkan!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
