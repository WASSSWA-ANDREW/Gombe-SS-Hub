<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@gombess.edu.ng'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+234-123-456-7890',
                'address' => 'Gombe State, Nigeria',
                'bio' => 'System Administrator for Gombe Secondary School Hub',
            ]
        );

        // Create super admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'superadmin@gombess.edu.ng'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'phone' => '+234-987-654-3210',
                'address' => 'Gombe State, Nigeria',
                'bio' => 'Super Administrator for Gombe Secondary School Hub',
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin Login: admin@gombess.edu.ng / admin123');
        $this->command->info('Super Admin Login: superadmin@gombess.edu.ng / superadmin123');
    }
}
