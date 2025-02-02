<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([

            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $user = User::factory()->create([

            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $user->assignRole('Admin');
        $admin->assignRole('Admin');
    }
}
