<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1. Create Roles

            $roles = [
                [
                    'name' => 'SuperAdmin',
                    'slug' => 'super-admin',
                ],
                [
                    'name' => 'Admin',
                    'slug' => 'admin',
                ],
                [
                    'name' => 'Member',
                    'slug' => 'member',
                ],
                [
                    'name' => 'Sales',
                    'slug' => 'sales',
                ],
                [
                    'name' => 'Manager',
                    'slug' => 'manager',
                ],
            ];

            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['slug' => $role['slug']],
                    [
                        'name' => $role['name'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // 2. Create Permissions

            $permissions = [
                [
                    'name' => 'Create Company',
                    'slug' => 'company.create',
                ],
                [
                    'name' => 'View Company',
                    'slug' => 'company.view',
                ],
                [
                    'name' => 'Update Company',
                    'slug' => 'company.update',
                ],
                [
                    'name' => 'Invite User',
                    'slug' => 'user.invite',
                ],
                [
                    'name' => 'View Users',
                    'slug' => 'user.view',
                ],
                [
                    'name' => 'Create Short URL',
                    'slug' => 'short_url.create',
                ],
                [
                    'name' => 'View Short URLs',
                    'slug' => 'short_url.view',
                ],
                [
                    'name' => 'Resolve Short URL',
                    'slug' => 'short_url.resolve',
                ],
                [
                    'name' => 'Delete Short URL',
                    'slug' => 'short_url.delete',
                ],
            ];

            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['slug' => $permission['slug']],
                    [
                        'name' => $permission['name'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // 3. Get Role IDs

            $roleIds = DB::table('roles')
                ->pluck('id', 'slug');

            // 4. Get Permission IDs

            $permissionIds = DB::table('permissions')
                ->pluck('id', 'slug');

            // 5. Assign Permissions to Roles

            $rolePermissions = [

                // SuperAdmin
                'super-admin' => [
                    'company.create',
                    'company.view',
                    'company.update',
                    'user.invite',
                    'user.view',
                    'short_url.view',
                    'short_url.resolve',
                ],

                // Admin
                'admin' => [
                    'user.invite',
                    'user.view',
                    'short_url.create',
                    'short_url.view',
                    'short_url.resolve',
                ],

                // Member
                'member' => [
                    'short_url.create',
                    'short_url.view',
                    'short_url.resolve',
                ],

                // Sales
                'sales' => [
                    'short_url.resolve',
                ],

                // Manager
                'manager' => [
                    'short_url.resolve',
                ],
            ];

            foreach ($rolePermissions as $roleSlug => $permissionsList) {

                $roleId = $roleIds[$roleSlug];

                foreach ($permissionsList as $permissionSlug) {

                    $permissionId = $permissionIds[$permissionSlug];

                    DB::table('role_permissions')->updateOrInsert(
                        [
                            'role_id' => $roleId,
                            'permission_id' => $permissionId,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        });
    }
}
