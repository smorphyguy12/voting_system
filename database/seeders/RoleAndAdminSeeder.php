<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class RoleAndAdminSeeder extends Seeder 
{
    public function run()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);

        // Create admin user
        User::create([
            'student_id' => 'admin',
            'full_name' => 'System Administrator',
            'email' => 'admin@university.edu',
            'password' => Hash::make('Admin_1234'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'course' => 'Administration'
        ]);
    }
}