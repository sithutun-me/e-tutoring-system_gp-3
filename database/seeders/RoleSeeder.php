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
        $roles = ['admin', 'tutor', 'student'];

        foreach ($roles as $role_name) {
            Role::firstOrCreate(['role_name' => $role_name]);
        }
    }
}
