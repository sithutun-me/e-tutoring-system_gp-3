<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PostSeeder::class,
            DocumentSeeder::class,
            CommentSeeder::class,
        ]);
        User::factory()->create([
            'user_code' => 'stf0001',
            'first_name' => 'Admin',
            'last_name' => 'Staff',
            'date_of_birth' => '1998-02-12',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
            'role_id' => 3,
        ]);

        User::factory()->create([
            'user_code' => 'tur0001',
            'first_name' => 'Tutor',
            'last_name' => 'User',
            'date_of_birth' => '1989-03-17',
            'email' => 'tutor@example.com',
            'password' => Hash::make('1234'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'user_code' => 'std0001',
            'first_name' => 'Student',
            'last_name' => 'User',
            'date_of_birth' => '1999-05-23',
            'email' => 'student@example.com',
            'password' => Hash::make('1234'),
            'role_id' => 1,
        ]);
    }
}