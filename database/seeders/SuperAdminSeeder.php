<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get SuperAdmin role

        $role = DB::table('roles')
            ->where('slug', 'super-admin')
            ->first();

        if (!$role) {
            throw new \Exception('SuperAdmin role not found. Run RolePermissionSeeder first.');
        }

        // SuperAdmin details

        $name = 'Super Admin';
        $email = 'superadmin@example.com';
        $password = 'Admin@123456';

        // Check existing account

        $existingUser = DB::table('users')
            ->where('email', $email)
            ->first();

        if ($existingUser) {
            $this->command->info('SuperAdmin already exists.');

            return;
        }

        // Hash password

        $hashedPassword = Hash::make($password);

        // Insert SuperAdmin using RAW SQL

        DB::insert(
            'INSERT INTO users
            (company_id, role_id, name, email, password, email_verified_at, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                null,
                $role->id,
                $name,
                $email,
                $hashedPassword,
                now(),
                now(),
                now(),
            ]
        );

        $this->command->info('SuperAdmin created successfully.');

        $this->command->info('Email: ' . $email);
        $this->command->info('Password: ' . $password);
    }
}
