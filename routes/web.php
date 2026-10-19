<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/peserta/terhapus', [PesertaController::class, 'terhapus'])->name('peserta.terhapus');
    Route::post('/peserta/{id}/restore', [PesertaController::class, 'restore'])->name('peserta.restore');
    Route::resource('peserta', PesertaController::class)->parameters([
        'peserta' => 'peserta',
    ]);

    Route::middleware(['admin'])->group(function (): void {
        Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
        Route::patch('/pesan/{pesan}/dibaca', [PesanController::class, 'tandaiDibaca'])->name('pesan.dibaca');
        Route::patch('/pesan/dibaca-semua', [PesanController::class, 'tandaiSemuaDibaca'])->name('pesan.dibaca-semua');
        Route::delete('/pesan/{pesan}', [PesanController::class, 'destroy'])->name('pesan.destroy');

        Route::get('/demo-pesan', [DemoController::class, 'index'])->name('demo.index');
        Route::get('/demo-pesan/contoh/{tipe}', [DemoController::class, 'contoh'])->name('demo.contoh');
        Route::get('/demo-pesan/error-500', [DemoController::class, 'picuError500'])->name('demo.500');
        Route::get('/demo-pesan/error-404', [DemoController::class, 'picuError404'])->name('demo.404');
        Route::get('/demo-pesan/error-403', [DemoController::class, 'picuError403'])->name('demo.403');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
