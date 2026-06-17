<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PenerimaController;
use App\Http\Controllers\Admin\DistribusiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\StatusPengirimanController;
use App\Http\Controllers\Petugas\DashboardPetugasController;
use App\Http\Controllers\Sekolah\DashboardSekolahController;

// Halaman utama otomatis lempar ke login
Route::redirect('/', '/login');

// Rute Login & Logout (Tidak dikunci)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ================= ROUTE ADMIN (Dikunci khusus 'admin') =================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/penerima', [App\Http\Controllers\Admin\PenerimaController::class, 'index'])->name('penerima.index');
    Route::get('/penerima/create', [App\Http\Controllers\Admin\PenerimaController::class, 'create'])->name('penerima.create');
    Route::post('/penerima', [App\Http\Controllers\Admin\PenerimaController::class, 'store'])->name('penerima.store');
    Route::get('/penerima/{sekolah}/edit', [App\Http\Controllers\Admin\PenerimaController::class, 'edit'])->name('penerima.edit');
    Route::put('/penerima/{sekolah}', [App\Http\Controllers\Admin\PenerimaController::class, 'update'])->name('penerima.update');
    Route::delete('/penerima/{sekolah}', [App\Http\Controllers\Admin\PenerimaController::class, 'destroy'])->name('penerima.destroy');

    Route::get('/distribusi', [App\Http\Controllers\Admin\DistribusiController::class, 'index'])->name('distribusi.index');
    Route::get('/distribusi/create', [App\Http\Controllers\Admin\DistribusiController::class, 'create'])->name('distribusi.create');
    Route::post('/distribusi', [App\Http\Controllers\Admin\DistribusiController::class, 'store'])->name('distribusi.store');
    Route::get('/distribusi/{distribusi}/edit', [App\Http\Controllers\Admin\DistribusiController::class, 'edit'])->name('distribusi.edit');
    Route::put('/distribusi/{distribusi}', [App\Http\Controllers\Admin\DistribusiController::class, 'update'])->name('distribusi.update');
    Route::delete('/distribusi/{distribusi}', [App\Http\Controllers\Admin\DistribusiController::class, 'destroy'])->name('distribusi.destroy');
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [App\Http\Controllers\Admin\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-pdf', [App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/kritik-saran', [App\Http\Controllers\Admin\KritikSaranController::class, 'index'])->name('kritik-saran.index');
    Route::get('/monitoring', [App\Http\Controllers\Admin\MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/chart-data', [App\Http\Controllers\Admin\MonitoringController::class, 'chartData'])->name('monitoring.chart-data');
    Route::get('/ompreng', [App\Http\Controllers\Admin\OmprengController::class, 'index'])->name('ompreng.index');
    Route::get('/pengguna', [App\Http\Controllers\Admin\PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [App\Http\Controllers\Admin\PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [App\Http\Controllers\Admin\PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{user}/edit', [App\Http\Controllers\Admin\PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{user}', [App\Http\Controllers\Admin\PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{user}', [App\Http\Controllers\Admin\PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    Route::get('/status-pengiriman', [App\Http\Controllers\Admin\StatusPengirimanController::class, 'index'])->name('status.index');
});

/// ================= ROUTE PETUGAS (Dikunci khusus 'petugas') =================
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'index'])->name('dashboard');
    Route::get('/konfirmasi', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'halamanKonfirmasi'])->name('konfirmasi.halaman');
    Route::post('/konfirmasi/{id}', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('/lapor-kendala/{id}', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'laporKendala'])->name('lapor-kendala');
    Route::post('/selesaikan/{id}', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'selesaiKirim'])->name('konfirmasi.selesai');
    Route::get('/riwayat', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', [App\Http\Controllers\Petugas\DashboardPetugasController::class, 'profil'])->name('profil');
});

// ================= ROUTE SEKOLAH (Dikunci khusus 'sekolah') =================
Route::prefix('sekolah')->name('sekolah.')->middleware(['auth', 'role:sekolah'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'index'])->name('dashboard');
    Route::get('/tracking', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'tracking'])->name('tracking');
    Route::get('/konfirmasi', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('/konfirmasi', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'storeKonfirmasi'])->name('konfirmasi.store');
    Route::get('/riwayat', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'riwayat'])->name('riwayat');
    Route::get('/kritik-saran', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'kritikSaran'])->name('kritik-saran');
    Route::post('/kritik-saran', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'storeKritikSaran'])->name('kritik-saran.store');
    Route::get('/pengembalian-ompreng', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'pengembalianOmpreng'])->name('pengembalian-ompreng');
    Route::post('/pengembalian-ompreng', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'storePengembalianOmpreng'])->name('pengembalian-ompreng.store');
    Route::get('/profil', [App\Http\Controllers\Sekolah\DashboardSekolahController::class, 'profil'])->name('profil');
});