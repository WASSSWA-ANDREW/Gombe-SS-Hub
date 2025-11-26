<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@gombess.edu.ng'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('superadmin123'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );
    }
}
