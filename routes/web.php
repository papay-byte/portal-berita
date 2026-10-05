<?php

use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/berita');

// Daftar berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');

// Form tambah berita.
// WAJIB di atas route {berita}! Kalau di bawahnya, kata "tambah" dianggap sebagai slug
// dan Laravel mencari berita ber-slug "tambah" (hasilnya 404).
Route::get('/berita/tambah', [BeritaController::class, 'create'])->name('berita.create');

// Menyimpan berita baru (dikirim dari form)
Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');

// Detail berita berdasarkan slug
Route::get('/berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');