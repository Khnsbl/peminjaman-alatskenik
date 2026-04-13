<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\LogAktivitasController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('akun', AkunController::class)->except(['edit', 'update', 'show']);
    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('alat', AlatController::class)->except(['show']);
    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);
    Route::get('/log', [LogAktivitasController::class, 'index'])->name('log.index');
});

// Route Petugas
Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    
    // Validasi Peminjaman
    Route::get('/peminjaman', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/approve', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');

    // Verifikasi Pengembalian
    Route::get('/pengembalian', [\App\Http\Controllers\Petugas\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}/verifikasi', [\App\Http\Controllers\Petugas\PengembalianController::class, 'verifikasi'])->name('pengembalian.verifikasi');
    Route::post('/pengembalian/{peminjaman}/konfirmasi-denda', [\App\Http\Controllers\Petugas\PengembalianController::class, 'konfirmasiDenda'])->name('pengembalian.konfirmasi-denda');

    // Kelola Alat
    Route::resource('alat', \App\Http\Controllers\Petugas\AlatController::class)->except(['show']);

    // Kelola Kategori
    Route::resource('kategori', \App\Http\Controllers\Petugas\KategoriController::class)->except(['show']);

    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\Petugas\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [\App\Http\Controllers\Petugas\LaporanController::class, 'cetak'])->name('laporan.cetak');
});

// Route User
Route::middleware(['auth', 'role.user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/alat', [\App\Http\Controllers\User\AlatController::class, 'index'])->name('alat.index');
    Route::resource('peminjaman', \App\Http\Controllers\User\PeminjamanController::class)->only(['index', 'create', 'store']);
    Route::get('/peminjaman/{peminjaman}/kembalikan', [\App\Http\Controllers\User\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('/peminjaman/{peminjaman}/konfirmasi', [\App\Http\Controllers\User\PeminjamanController::class, 'konfirmasi'])->name('peminjaman.konfirmasi');
});

require __DIR__.'/auth.php';