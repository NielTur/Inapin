<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BerandaController;
use App\Http\Controllers\Frontend\VillaController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\AkunController;
use App\Http\Controllers\Frontend\PembayaranController;
use App\Http\Controllers\Frontend\UlasanController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\VillaController as OwnerVillaController;
use App\Http\Controllers\Owner\PesananController as OwnerPesananController;
use App\Http\Controllers\Owner\AuthController as OwnerAuthController;
use App\Http\Controllers\Owner\AkunController as OwnerAkunController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// ===================================================================
// FRONTEND (Tamu / Customer)
// ===================================================================
Route::prefix('')->group(function () {

    // PUBLIC
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::get('/villa', [VillaController::class, 'index'])->name('villa.index');
    Route::get('/villa/{id}', [VillaController::class, 'detail'])->name('villa.detail');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Callback Midtrans (no CSRF)
    Route::post('/pembayaran/callback', [PembayaranController::class, 'callback'])
        ->name('pembayaran.callback')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // PROTECTED — harus login sebagai Customer
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Akun
        Route::get('/akun/profil', [AkunController::class, 'profil'])->name('akun.profil');
        Route::post('/akun/profil', [AkunController::class, 'updateProfil'])->name('akun.updateProfil');

        // Ulasan
        Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

        // Booking — URUTAN WAJIB: riwayat → {id}/detail → {id}/bayar → {id}
        Route::get('/booking/riwayat', [BookingController::class, 'riwayat'])->name('booking.riwayat');
        Route::get('/booking/{id}/detail', [BookingController::class, 'detail'])->name('booking.detail');
        Route::get('/booking/{id}/bayar', [BookingController::class, 'bayar'])->name('booking.bayar');
        Route::get('/booking/{id}', [BookingController::class, 'form'])->name('booking.form');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::delete('/booking/{id}/batal', [BookingController::class, 'batal'])->name('booking.batal');
        Route::delete('/booking/{id}/hapus', [BookingController::class, 'hapus'])->name('booking.hapus');

        // Pembayaran
        Route::get('/pembayaran/finish', [PembayaranController::class, 'finish'])->name('pembayaran.finish');
    });
});

// ===================================================================
// OWNER
// ===================================================================
Route::prefix('owner')->name('owner.')->group(function () {

    Route::get('/login', [OwnerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [OwnerAuthController::class, 'login']);
    Route::get('/register', [OwnerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [OwnerAuthController::class, 'register']);

    Route::middleware(['auth:owner'])->group(function () {
        Route::post('/logout', [OwnerAuthController::class, 'logout'])->name('logout');

        Route::get('/akun/profil', [OwnerAkunController::class, 'profil'])->name('akun.profil');
        Route::post('/akun/profil', [OwnerAkunController::class, 'updateProfil'])->name('akun.updateProfil');

        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/villa', [OwnerVillaController::class, 'index'])->name('villa.index');
        Route::get('/villa/create', [OwnerVillaController::class, 'create'])->name('villa.create');
        Route::post('/villa', [OwnerVillaController::class, 'store'])->name('villa.store');
        Route::get('/villa/{id}/edit', [OwnerVillaController::class, 'edit'])->name('villa.edit');
        Route::put('/villa/{id}', [OwnerVillaController::class, 'update'])->name('villa.update');
        Route::delete('/villa/{id}', [OwnerVillaController::class, 'destroy'])->name('villa.destroy');

        Route::get('/pesanan', [OwnerPesananController::class, 'index'])->name('pesanan.index');
        Route::patch('/pesanan/{id}/checkin', [OwnerPesananController::class, 'checkin'])->name('pesanan.checkin');
        Route::patch('/pesanan/{id}/checkout', [OwnerPesananController::class, 'checkout'])->name('pesanan.checkout');
    });
});

// ===================================================================
// SUPER ADMIN
// ===================================================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/beranda', [\App\Http\Controllers\Admin\BerandaController::class, 'index'])->name('beranda');

        Route::get('/customer', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customer.index');
        Route::delete('/customer/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customer.destroy');

        Route::get('/owner', [\App\Http\Controllers\Admin\OwnerController::class, 'index'])->name('owner.index');
        Route::delete('/owner/{id}', [\App\Http\Controllers\Admin\OwnerController::class, 'destroy'])->name('owner.destroy');

        Route::get('/villa', [\App\Http\Controllers\Admin\VillaController::class, 'index'])->name('villa.index');
        Route::get('/villa/{id}', [\App\Http\Controllers\Admin\VillaController::class, 'show'])->name('villa.show');
        Route::patch('/villa/{id}/status', [\App\Http\Controllers\Admin\VillaController::class, 'updateStatus'])->name('villa.status');
        Route::patch('/villa/{id}/setujui', [\App\Http\Controllers\Admin\VillaController::class, 'setujui'])->name('villa.setujui');
        Route::patch('/villa/{id}/tolak', [\App\Http\Controllers\Admin\VillaController::class, 'tolak'])->name('villa.tolak');
        Route::patch('/villa/{id}/nonaktifkan', [\App\Http\Controllers\Admin\VillaController::class, 'nonaktifkan'])->name('villa.nonaktifkan');
        Route::patch('/villa/{id}/aktifkan', [\App\Http\Controllers\Admin\VillaController::class, 'aktifkan'])->name('villa.aktifkan');
        Route::delete('/villa/{id}', [\App\Http\Controllers\Admin\VillaController::class, 'destroy'])->name('villa.destroy');

        Route::get('/pesanan', [\App\Http\Controllers\Admin\PesananController::class, 'index'])->name('pesanan.index');
    });
});