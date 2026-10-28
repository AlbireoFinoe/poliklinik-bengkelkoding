
<?php

use Illuminate\Support\Facades\Route;

// Authentication Controller
use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;

// Dokter Controllers
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;

// Pasien Controllers
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    AuthController::class, 'showLogin'
])->name('login');

Route::post('/login', [
    AuthController::class, 'login'
]);

Route::get('/register', [
    AuthController::class, 'showRegister'
]);

Route::post('/register', [
    AuthController::class, 'register'
])->name('register');

Route::post('/logout', [
    AuthController::class, 'logout'
])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard Admin
        Route::view('/dashboard', 'admin.dashboard')
            ->name('admin.dashboard');

        // CRUD Poli
        Route::resource(
            'polis',
            AdminPoliController::class
        );

        // CRUD Dokter
        Route::resource(
            'dokter',
            DokterController::class
        );

        // CRUD Pasien
        Route::resource(
            'pasien',
            PasienController::class
        );

        // CRUD Obat
        Route::resource(
            'obat',
            ObatController::class
        )->except(['show']);

    });


/*
|--------------------------------------------------------------------------
| DOKTER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->group(function () {

        // Dashboard Dokter
        Route::view('/dashboard', 'dokter.dashboard')
            ->name('dokter.dashboard');

        // CRUD Jadwal Periksa
        Route::resource(
            'jadwal-periksa',
            JadwalPeriksaController::class
        )
            ->parameters([
                'jadwal-periksa' => 'jadwalPeriksa',
            ])
            ->except(['show']);

        // Daftar Pasien yang Akan Diperiksa
        Route::get(
            '/periksa-pasien',
            [PeriksaPasienController::class, 'index']
        )->name('periksa-pasien.index');

        // Form Pemeriksaan Pasien
        Route::get(
            '/periksa-pasien/{id}',
            [PeriksaPasienController::class, 'create']
        )
            ->whereNumber('id')
            ->name('periksa-pasien.create');

        // Simpan Pemeriksaan Pasien
        Route::post(
            '/periksa-pasien',
            [PeriksaPasienController::class, 'store']
        )->name('periksa-pasien.store');

        // Riwayat Pemeriksaan Pasien
        Route::get(
            '/riwayat-pasien',
            [RiwayatPasienController::class, 'index']
        )->name('riwayat-pasien.index');

        // Detail Riwayat Pemeriksaan
        Route::get(
            '/riwayat-pasien/{id}',
            [RiwayatPasienController::class, 'show']
        )
            ->whereNumber('id')
            ->name('riwayat-pasien.show');

    });


/*
|--------------------------------------------------------------------------
| PASIEN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->group(function () {

        // Dashboard Pasien
        Route::view('/dashboard', 'pasien.dashboard')
            ->name('pasien.dashboard');

        // Form Pendaftaran Poli
        Route::get(
            '/daftar',
            [PasienPoliController::class, 'get']
        )->name('pasien.daftar');

        // Simpan Pendaftaran Poli
        Route::post(
            '/daftar',
            [PasienPoliController::class, 'submit']
        )->name('pasien.daftar.submit');

    });
