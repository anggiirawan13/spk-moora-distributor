<?php

namespace Database\Seeders;

use \App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'image_name' => '',
            'password' => bcrypt('admin123'),
            'is_admin' => 1,
        ]);

        User::create([
            'name' => 'staf',
            'email' => 'staf@staf.com',
            'image_name' => '',
            'password' => bcrypt('staf123'),
            'is_admin' => 0,
        ]);
    }
}
