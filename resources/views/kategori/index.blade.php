@extends('layouts.app')

@section('header', 'Data Kategori')

@section('content')
<div x-data="{ addModal: false, editModal: false, deleteModal: false, editData: {}, deleteUrl: '', deleteName: '' }">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        
        <!-- Search -->
        <form method="GET" action="{{ route('kategori.index') }}" class="w-full sm:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </form>

        <!-- Add Button -->
        <button @click="addModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-20">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 w-40">Jumlah Barang</th>
                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kategoris as $key => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">{{ $kategoris->firstItem() + $key }}</td>
                        <td class="px-6 py-4 font-medium">{{ $item->nama_kategori }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $item->barangs_count ?? $item->barangs()->count() }} Barang
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center space-x-2">
                            <!-- Edit -->
                            <button @click="editData = {{ json_encode($item) }}; editModal = true" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <!-- Delete -->
                            <button @click="deleteUrl = '{{ route('kategori.destroy', $item->id) }}'; deleteName = '{{ $item->nama_kategori }}'; deleteModal = true" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($kategoris->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $kategoris->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

    <!-- Modal Tambah Kategori -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="addModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="addModal = false"></div>
        <div x-show="addModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-800">Tambah Kategori Baru</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="editModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="editModal = false"></div>
        <div x-show="editModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-800">Edit Kategori</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            
            <form :action="'{{ url('kategori') }}/' + editData.id" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" x-model="editData.nama_kategori" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Hapus Kategori -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="deleteModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteModal = false"></div>
        <div x-show="deleteModal" x-transition.scale.origin.bottom class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4">
                    <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
                <p class="text-slate-500 text-sm mb-8">Apakah Anda yakin ingin menghapus kategori <span class="font-semibold text-slate-800" x-text="deleteName"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                
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
