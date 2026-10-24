<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // forceCreate dipakai karena 'role' sengaja tidak ada di $fillable.
        // Password 'password' otomatis di-hash oleh cast 'hashed'. Ganti untuk penggunaan sungguhan!
        User::forceCreate([
            'name'     => 'Admin Desa',
            'email'    => 'admin@jalatrang.test',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        User::forceCreate([
            'name'     => 'Editor Berita',
            'email'    => 'editor@jalatrang.test',
            'password' => 'password',
            'role'     => 'editor',
        ]);
    }
}