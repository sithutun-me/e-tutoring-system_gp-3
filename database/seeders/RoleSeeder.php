<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['id' => 1, 'role_name' => 'student'],
            ['id' => 2, 'role_name' => 'tutor'],
            ['id' => 3, 'role_name' => 'admin'],
        ]);
    }
}
