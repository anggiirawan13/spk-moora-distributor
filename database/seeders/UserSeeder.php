<?php

namespace Database\Seeders;

use \App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
            'phone' => '08123456789',
            'address' => 'Tangerang',
            'password' => bcrypt('admin123'),
            'is_admin' => 1,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        User::create([
            'name' => 'staf',
            'email' => 'staf@staf.com',
            'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
            'phone' => '08123456789',
            'address' => 'Tangerang',
            'password' => bcrypt('staf123'),
            'is_admin' => 0,
            'role' => 'staf',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        User::create([
            'name' => 'komisaris',
            'email' => 'komisaris@komisaris.com',
            'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
            'phone' => '08123456789',
            'address' => 'Tangerang',
            'password' => bcrypt('komisaris123'),
            'is_admin' => 0,
            'role' => 'komisaris',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        User::create([
            'name' => 'dirut',
            'email' => 'dirut@dirut.com',
            'image_name' => 'Ex1y3eAnda4xT6AiP1j93VCJ9HQpTEmBMzaMLJsf.jpg',
            'phone' => '08123456789',
            'address' => 'Tangerang',
            'password' => bcrypt('dirut123'),
            'is_admin' => 0,
            'role' => 'direktur_utama',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
