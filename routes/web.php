<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\DashboardController;

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

        Route::resource('/dokter', DokterController::class);
        Route::resource('/pasien', PasienController::class);
        Route::resource('/rekam_medis', RekamMedisController::class);

});




