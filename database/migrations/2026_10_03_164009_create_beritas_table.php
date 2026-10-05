<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Method up() dijalankan saat "php artisan migrate" -> membuat tabel
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();                          // Kolom id (primary key, auto increment)
            $table->string('judul');               // Judul berita (VARCHAR 255)
            $table->string('slug')->unique();      // Slug untuk URL, harus unik
            $table->date('tanggal');               // Tanggal terbit berita
            $table->string('penulis');             // Nama penulis
            $table->text('ringkasan');             // Ringkasan singkat (tampil di kartu)
            $table->longText('konten');            // Isi berita lengkap
            $table->string('gambar')->nullable();  // URL/path gambar (boleh kosong)
            $table->timestamps();                  // Kolom created_at & updated_at
        });
    }

    // Method down() dijalankan saat rollback -> menghapus tabel
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};