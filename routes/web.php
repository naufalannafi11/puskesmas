<?php

use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Pasien\ReservasiController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Dokter\PemeriksaanController;
use App\Http\Controllers\Pasien\RiwayatController;
use App\Models\Obat;
use App\Http\Controllers\Admin\PrediksiController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->get('/admin', fn() =>
        view('dashboard.admin')
    )->name('admin.dashboard');

    Route::middleware('role:dokter')->get('/dokter', fn() =>
        view('dashboard.dokter')
    )->name('dokter.dashboard');

    Route::middleware('role:pasien')->get('/pasien', fn() =>
        view('dashboard.pasien')
    )->name('pasien.dashboard');
});

Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

       Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/dataset/import', function () {
    return view('admin.dataset.import');
});

        Route::resource('/dokter', DokterController::class);
        Route::resource('/pasien', PasienController::class);
        Route::resource('/rekam_medis', RekamMedisController::class);
        Route::resource('/obat', ObatController::class);
        Route::get('/pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::post('/pembayaran/{id}/bayar', [PembayaranController::class, 'bayar'])
            ->name('pembayaran.bayar');

        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])
            ->name('pembayaran.show');
        
        Route::get('/prediksi', [PrediksiController::class, 'index'])
    ->name('prediksi.index');

Route::post('/prediksi', [PrediksiController::class, 'predict'])
    ->name('prediksi.proses');

});

Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function(){
        Route::get('/', [PasienDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/reservasi', ReservasiController::class)
            ->only(['index','create','store']);

        Route::get('/rekam-medis', [RiwayatController::class, 'index'])
            ->name('riwayat');
        
            Route::get('/rekam-medis/{id}', [RiwayatController::class, 'show'])
            ->name('riwayat.show');

    });

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->name('dokter.')
    ->group(function(){

        Route::get('/pemeriksaan',[PemeriksaanController::class, 'index'])
        ->name('pemeriksaan.index');
        Route::get('/pemeriksaan/{reservasi}/create', [PemeriksaanController::class, 'create'])
        ->name('pemeriksaan.create');

        Route::post('/pemeriksaan/{reservasi}', [PemeriksaanController::class,'store'])
        ->name('pemeriksaan.store');

        Route::get('/pemeriksaan/riwayat', 
    [PemeriksaanController::class, 'riwayat']
)->name('pemeriksaan.riwayat');

Route::get('/pemeriksaan/{id}', 
[PemeriksaanController::class, 'show']
)->name('pemeriksaan.show');
    });

Route::get('/get-dokter-by-poli/{poli}', function ($poli) {
    return User::where('role', 'dokter')
        ->where('poli', $poli)
        ->get(['id', 'name']);
});





