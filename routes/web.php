<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\LaporanController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('api.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('barang', BarangController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('user', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // Transaksi
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'store', 'destroy']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'store', 'destroy']);
    Route::resource('saldo', SaldoController::class)->only(['index', 'store', 'destroy']);
    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
});