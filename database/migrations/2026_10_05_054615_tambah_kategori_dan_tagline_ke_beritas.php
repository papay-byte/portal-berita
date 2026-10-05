<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table = mengubah tabel yang SUDAH ada (bukan membuat baru)
        Schema::table('beritas', function (Blueprint $table) {
            // Foreign key: kolom kategori_id menunjuk ke kolom id di tabel kategoris.
            // nullable()    = berita boleh tanpa kategori
            // nullOnDelete  = jika kategori dihapus, kategori_id di berita menjadi NULL (berita tidak ikut terhapus)
            $table->foreignId('kategori_id')->nullable()->after('id')
                  ->constrained('kategoris')->nullOnDelete();

            // Tagline: kata dipisah koma, mis. "cikandung,dusun,2026"
            $table->string('tagline')->nullable()->after('gambar');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id'); // hapus foreign key + kolomnya
            $table->dropColumn('tagline');
        });
    }
};