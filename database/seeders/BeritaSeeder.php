<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $editor     = User::where('email', 'editor@jalatrang.test')->first();
        $olahraga   = Kategori::where('slug', 'olahraga')->first();
        $pendidikan = Kategori::where('slug', 'pendidikan')->first();

        $berita1 = Berita::create([
            'kategori_id' => $olahraga->id,
            'user_id'     => $editor->id,
            'judul'       => 'Malam Penuh Gengsi Dimulai! 16 Tim Berebut Mahkota Juara di Ajang CVC Cup 2026 Desa Jalatrang',
            'slug'        => 'malam-penuh-gengsi-dimulai-16-tim-berebut-mahkota-juara-di-ajang-cvc-cup-2026-desa-jalatrang',
            'tanggal'     => '2026-09-26',
            'ringkasan'   => 'JalatrangNews; Himpunan Pemuda-Pemudi Dusun Cikandung yang tergabung dalam Cikandung Voli...',
            'konten'      => "Ini adalah konten dummy untuk keperluan belajar.\n\nBerita lengkap dapat dibaca di situs aslinya: https://jalatrang.id/berita",
            'gambar'      => 'https://jalatrang.id/assets/images/web_berita/1790662317-whatsapp-image-2026-09-27-at-081321.jpeg',
        ]);
        // attach(): mengisi tabel pivot berita_tag dengan id tag yang dipilih
        $berita1->tags()->attach(Tag::whereIn('slug', ['cikandung', 'dusun', '2026'])->pluck('id'));

        $berita2 = Berita::create([
            'kategori_id' => $pendidikan->id,
            'user_id'     => $editor->id,
            'judul'       => 'Pemerintah Desa Jalatrang Kukuhkan Desa Siaga TB, Perkuat Kolaborasi Lintas Sektor Basmi Tuberkulosis',
            'slug'        => 'pemerintah-desa-jalatrang-kukuhkan-desa-siaga-tb-perkuat-kolaborasi-lintas-sektor-basmi-tuberkulosis',
            'tanggal'     => '2026-09-28',
            'ringkasan'   => 'JalatrangNews; Pemerintah Desa Jalatrang, Kecamatan Cipaku, Kabupaten Ciamis, menunjukkan...',
            'konten'      => "Ini adalah konten dummy untuk keperluan belajar.\n\nBerita lengkap dapat dibaca di situs aslinya: https://jalatrang.id/berita",
            'gambar'      => 'https://jalatrang.id/assets/images/web_berita/1790660451-whatsapp-image-2026-09-28-at-102755.jpeg',
        ]);
        $berita2->tags()->attach(Tag::whereIn('slug', ['desa', 'jalatrang', 'siaga'])->pluck('id'));
    }
}