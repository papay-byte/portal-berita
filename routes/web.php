<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/berita');

// ----- Publik (tanpa login) -----
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');

// ----- Autentikasi -----
// Route bernama 'login' WAJIB ada: middleware 'auth' mengarahkan tamu ke route ini
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
// throttle:5,1 = maksimal 5 percobaan login per menit (mencegah tebak password)
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ----- Harus login (admin dan editor) -----
// /berita/tambah HARUS di atas /berita/{berita}, kalau tidak kata "tambah" dianggap slug
Route::middleware('auth')->group(function () {
    Route::get('/berita/tambah', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
});

// ----- Hanya admin: login + middleware 'admin' (IsAdmin) -----
Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('berita.destroy');

// ----- Detail berita (paling bawah karena memakai {berita}) -----
Route::get('/berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');