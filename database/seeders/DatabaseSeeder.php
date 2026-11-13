<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // First create roles and permissions

        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Then create SuperAdmin

        $this->call([
            SuperAdminSeeder::class,
        ]);
    }
}
