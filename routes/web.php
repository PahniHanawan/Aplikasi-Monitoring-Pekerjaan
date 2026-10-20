<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenProyekController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kantor
    Route::resource('kantor', KantorController::class);

    // CRUD Proyek
    Route::resource('proyek', ProyekController::class);
    Route::get('/proyek/{proyek}/download-izin', [ProyekController::class, 'downloadIzin'])->name('proyek.download-izin');

    // CRUD Kontrak
    Route::resource('kontrak', KontrakController::class);
    Route::get('/kontrak/{kontrak}/download-spk', [KontrakController::class, 'downloadSpk'])
        ->name('kontrak.download-spk');

    // Route untuk manajemen biaya di dalam kontrak
    Route::post('/kontrak/{kontrak}/tambah-termin', [KontrakController::class, 'tambahTermin'])
        ->name('kontrak.tambah-termin');
    Route::delete('/biaya/{biaya}', [KontrakController::class, 'hapusTermin'])
        ->name('kontrak.hapus-termin');

    // CRUD Realisasi
    Route::resource('realisasi', RealisasiController::class);

    // Dokumen Proyek Routes
    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::get('/', [DokumenProyekController::class, 'index'])->name('index');
        Route::get('/proyek/{proyek}', [DokumenProyekController::class, 'show'])->name('show');
        Route::get('/download/{dokumen}', [DokumenProyekController::class, 'download'])
            ->name('download');
        Route::delete('/{dokumen}', [DokumenProyekController::class, 'destroy'])
            ->name('destroy');
    });

    // Dokumen Proyek yang terkait dengan proyek tertentu
    Route::prefix('proyek/{proyek}')->group(function () {
        Route::get('/dokumen/create', [DokumenProyekController::class, 'create'])
            ->name('proyek.dokumen.create');
        Route::post('/dokumen', [DokumenProyekController::class, 'store'])
            ->name('proyek.dokumen.store');
    });

    // API Routes untuk data dinamis
    Route::prefix('api')->group(function () {
        // Check duplicate bagian
        Route::post('/check-bagian-duplicate', [KontrakController::class, 'checkBagianDuplicate'])
            ->name('api.check-bagian-duplicate');

        // Get kontrak by kantor
        Route::get('/kontrak-by-kantor/{kantorId}', function($kantorId) {
            $kontraks = \App\Models\Kontrak::whereHas('proyek', function($q) use ($kantorId) {
                $q->where('kantor_id', $kantorId);
            })->with('proyek')->get();

            return response()->json($kontraks);
        })->name('api.kontrak-by-kantor');

        // Stats
        Route::get('/kantor-stats', [KantorController::class, 'getStats'])->name('api.kantor-stats');
        Route::get('/proyek-by-kantor/{kantorId}', [ProyekController::class, 'getByKantor'])->name('api.proyek-by-kantor');
        Route::get('/kontrak-by-proyek/{proyekId}', [KontrakController::class, 'getByProyek'])->name('api.kontrak-by-proyek');
        Route::get('/realisasi-stats', [RealisasiController::class, 'getStats'])->name('api.realisasi-stats');
        Route::get('/dokumen-stats', [DokumenProyekController::class, 'getStats'])->name('api.dokumen-stats');
    });

    // Tambahkan route untuk API dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/filter', [DashboardController::class, 'filter'])->name('filter');
    Route::get('/events', [DashboardController::class, 'events'])->name('events');
    Route::get('/alerts', [DashboardController::class, 'alerts'])->name('alerts');
    Route::post('/clear-cache', [DashboardController::class, 'clearCache'])->name('clear-cache');
});

    // User Management Routes - HANYA untuk ADMIN
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Additional routes
        Route::get('/{user}/change-password', [UserController::class, 'showChangePasswordForm'])->name('change-password');
        Route::put('/{user}/change-password', [UserController::class, 'changePassword'])->name('update-password');
        Route::post('/{user}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{user}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
    });
});
