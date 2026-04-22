<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

// AUTH
use App\Http\Controllers\AuthController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PrediksiController;
use App\Http\Controllers\Admin\JadwalDokterController;

// IMPORT DATASET
use App\Http\Controllers\DatasetController;

// PASIEN
use App\Http\Controllers\Pasien\ReservasiController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Pasien\JadwalDokterController as PasienJadwalController;
use App\Http\Controllers\Pasien\RiwayatController;

// DOKTER
use App\Http\Controllers\Dokter\PemeriksaanController;

// MODEL
use App\Models\User;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ROLE REDIRECT DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/admin', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/dokter', [\App\Http\Controllers\Dokter\DashboardController::class, 'index'])
        ->middleware('role:dokter')
        ->name('dokter.dashboard');

    // PROFILE (Shared by all roles)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // DASHBOARD
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |----------------------------
        | DATASET IMPORT
        |----------------------------
        */
        Route::get('/dataset/import', fn() => view('admin.dataset.import'))
            ->name('dataset.import');

        Route::post('/dataset/import', [DatasetController::class, 'import'])
            ->name('dataset.import.proses');

        Route::get('/dataset/progress/{id}', [DatasetController::class, 'progress'])
            ->name('dataset.import.progress');

        /*
        |----------------------------
        | MASTER DATA
        |----------------------------
        */
        Route::resource('/dokter', DokterController::class);
        Route::resource('/pasien', PasienController::class);
        Route::resource('/rekam_medis', RekamMedisController::class)->only(['index', 'show', 'destroy']);
        Route::resource('/obat', ObatController::class);
        Route::resource('/jadwal-dokter', JadwalDokterController::class);

        /*
        |----------------------------
        | PEMBAYARAN
        |----------------------------
        */
        Route::get('/pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])
            ->name('pembayaran.show');

        Route::post('/pembayaran/{id}/bayar', [PembayaranController::class, 'bayar'])
            ->name('pembayaran.bayar');

        /*
        |----------------------------
        | PREDIKSI
        |----------------------------
        */
        Route::get('/prediksi', [PrediksiController::class, 'index'])
            ->name('prediksi.index');

        Route::post('/prediksi', [PrediksiController::class, 'predict'])
            ->name('prediksi.proses');

        // LAPORAN & STATISTIK
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])
            ->name('laporan.index');

        // ANTREAN MONITOR
        Route::get('/antrean', [\App\Http\Controllers\Admin\AntreanController::class, 'index'])
            ->name('antrean.index');
    });

/*
|--------------------------------------------------------------------------
| PASIEN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {

        Route::get('/', [PasienDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/jadwal-dokter', [PasienJadwalController::class, 'index'])
            ->name('jadwal.index');

        Route::resource('/reservasi', ReservasiController::class)
            ->only(['index', 'create', 'store']);

        Route::get('/rekam-medis', [RiwayatController::class, 'index'])
            ->name('riwayat');

        Route::get('/rekam-medis/{id}', [RiwayatController::class, 'show'])
            ->name('riwayat.show');
    });

/*
|--------------------------------------------------------------------------
| DOKTER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function () {

        Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])
            ->name('pemeriksaan.index');

        Route::get('/pemeriksaan/{reservasi}/create', [PemeriksaanController::class, 'create'])
            ->name('pemeriksaan.create');

        Route::post('/pemeriksaan/{reservasi}', [PemeriksaanController::class, 'store'])
            ->name('pemeriksaan.store');

        Route::get('/pemeriksaan/riwayat', [PemeriksaanController::class, 'riwayat'])
            ->name('pemeriksaan.riwayat');

        Route::post('/pemeriksaan/{reservasi}/panggil', [PemeriksaanController::class, 'panggil'])
            ->name('pemeriksaan.panggil');

        Route::get('/pemeriksaan/{id}', [PemeriksaanController::class, 'show'])
            ->name('pemeriksaan.show');

        // JADWAL
        Route::get('/jadwal', [\App\Http\Controllers\Dokter\JadwalController::class, 'index'])
            ->name('jadwal.index');
    });

/*
|--------------------------------------------------------------------------
| AJAX
|--------------------------------------------------------------------------
*/

Route::get('/get-dokter-by-poli/{poli}', function ($poli) {
    return User::where('role', 'dokter')
        ->where('poli', $poli)
        ->get(['id', 'name']);
});

Route::get('/get-dokter-by-jadwal', function (Request $request) {

    $hari = Carbon::parse($request->tanggal)
        ->locale('id')
        ->isoFormat('dddd');

    return User::where('role', 'dokter')
        ->where('poli', $request->poli)
        ->whereHas('jadwalDokter', function ($q) use ($hari) {
            $q->whereRaw('LOWER(hari) = ?', [strtolower($hari)]);
        })
        ->get(['id', 'name']);
});