<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_tag', function (Blueprint $table) {
            $table->id();

            // cascadeOnDelete: jika berita atau tag dihapus, baris pivotnya ikut terhapus otomatis
            $table->foreignId('berita_id')->constrained('beritas')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();

            // Satu berita tidak boleh punya tag yang sama dua kali
            $table->unique(['berita_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_tag');
    }
};