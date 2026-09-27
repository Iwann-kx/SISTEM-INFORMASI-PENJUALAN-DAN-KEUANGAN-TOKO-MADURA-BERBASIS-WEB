<?php

use App\Http\Controllers\WarungController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));
Route::get('/dashboard', [WarungController::class, 'dashboard'])->name('dashboard');

Route::get('/stok', [WarungController::class, 'stok'])->name('stok');
Route::post('/stok/barang', [WarungController::class, 'storeBarang'])->name('barang.store');
Route::put('/stok/barang/{barang}', [WarungController::class, 'updateBarang'])->name('barang.update');
Route::delete('/stok/barang/{barang}', [WarungController::class, 'destroyBarang'])->name('barang.destroy');

Route::get('/penjualan', [WarungController::class, 'penjualan'])->name('penjualan');
Route::post('/penjualan', [WarungController::class, 'storePenjualan'])->name('penjualan.store');

Route::get('/pembelian', [WarungController::class, 'pembelian'])->name('pembelian');
Route::post('/pembelian', [WarungController::class, 'storePembelian'])->name('pembelian.store');
Route::post('/supplier', [WarungController::class, 'storeSupplier'])->name('supplier.store');

Route::get('/laporan', [WarungController::class, 'laporan'])->name('laporan');
