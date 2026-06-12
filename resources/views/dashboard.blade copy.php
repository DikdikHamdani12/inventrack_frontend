@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="text-2xl font-semibold text-slate-800">Dashboard Visualisasi</h2>
    <p class="text-slate-500 text-sm">Ringkasan inventori logistik, transaksi keuangan, dan stok gudang terkini</p>
</div>

<!-- Stats Card -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Card 1: Total Jenis Barang -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
        <div class="flex items-center space-x-4 mb-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <h3 class="text-slate-500 font-medium">Total Barang</h3>
        </div>
        <div class="text-3xl font-bold text-slate-800">{{ $totalBarang }} <span class="text-sm font-normal text-slate-500">item</span></div>
    </div>
    
    <!-- Card 2: Barang Masuk -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
        <div class="flex items-center space-x-4 mb-3">
            <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
            </div>
            <h3 class="text-slate-500 font-medium">Masuk (Bulan Ini)</h3>
        </div>
        <div class="text-2xl font-bold text-teal-600">Rp {{ number_format($totalMasukNominal, 0, ',', '.') }}</div>
        <div class="text-sm text-slate-500 mt-1">Volume: <span class="font-semibold">{{ $totalMasuk }}</span> transaksi</div>
    </div>
    
    <!-- Card 3: Barang Keluar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow">
        <div class="flex items-center space-x-4 mb-3">
            <div class="p-3 bg-rose-100 text-rose-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            </div>
            <h3 class="text-slate-500 font-medium">Keluar (Bulan Ini)</h3>
        </div>
        <div class="text-2xl font-bold text-rose-600">Rp {{ number_format($totalKeluarNominal, 0, ',', '.') }}</div>
        <div class="text-sm text-slate-500 mt-1">Volume: <span class="font-semibold">{{ $totalKeluar }}</span> transaksi</div>
    </div>

    <!-- Card 4: Saldo Kas Gudang -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="flex items-center space-x-4 mb-3">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-slate-500 font-medium">Saldo Kas Saat Ini</h3>
        </div>
        <div class="text-2xl font-bold text-emerald-700">Rp {{ number_format(\App\Models\Saldo::getRunningSaldo(), 0, ',', '.') }}</div>
        <div class="text-sm text-slate-500 mt-1">Status Peringatan: <span class="font-semibold {{ $stokMenipis > 0 ? 'text-red-500' : 'text-emerald-600' }}">{{ $stokMenipis }} Stok Menipis</span></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Grafik Transaksi (7 Hari Terakhir)</h3>
        <div class="relative h-72 w-full">
            <canvas id="trxChart"></canvas>
        </div>
    </div>

    <!-- Peringatan Stok -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col h-full">
        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            Peringatan Stok Menipis
        </h3>
        
        <div class="flex-1 overflow-y-auto pr-2">
            @if(count($barangsMenipis) > 0)
                <ul class="space-y-3">
                    @foreach($barangsMenipis as $item)
                    <li class="flex justify-between items-center p-3 rounded bg-slate-50 border border-slate-100">
                        <div>
                            <p class="font-medium text-sm text-slate-800">{{ $item->nama_barang }}</p>
                            <p class="text-xs text-slate-500">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                        </div>
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Sisa {{ $item->stok }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="flex flex-col flex-1 items-center justify-center text-center text-slate-400 py-10">
                    <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Semua stok barang dalam kondisi aman.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mx = @json($chartMasuk);
        const kx = @json($chartKeluar);

        // Extract dates and compile into unique array
        let dates = new Set();
        mx.forEach(item => dates.add(item.date));
        kx.forEach(item => dates.add(item.date));
        let labels = Array.from(dates).sort();

        let dataMasuk = labels.map(date => {
            let found = mx.find(i => i.date === date);
            return found ? parseInt(found.total) : 0;
        });

        let dataKeluar = labels.map(date => {
            let found = kx.find(i => i.date === date);
            return found ? parseInt(found.total) : 0;
        });

        const ctx = document.getElementById('trxChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: dataMasuk,
                        backgroundColor: 'rgba(56, 189, 248, 0.8)', // Tailwind Sky-400
                        borderRadius: 4,
                        maxBarThickness: 40
                    },
                    {
                        label: 'Barang Keluar',
                        data: dataKeluar,
                        backgroundColor: 'rgba(251, 113, 133, 0.8)', // Tailwind Rose-400
                        borderRadius: 4,
                        maxBarThickness: 40
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(226, 232, 240, 1)' } }, // slate-200
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
