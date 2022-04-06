<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\RolePermission\Role;
use App\Models\RolePermission\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_permissions = [
            "Weather",
            "Role",
            "User",
        ];
        $child_permissions = array(
            'Role' => array(
                0 => 'create_role',
                1 => 'edit_role',
                2 => 'show_role',
                3 => 'delete_role',
            ),
            'User' => array(
                0 => 'create_user',
                1 => 'edit_user',
                2 => 'show_user',
                3 => 'delete_user',
            ),
            'Weather Record' => array(
                0 => 'edit_weather_record',
                1 => 'show_weather_record',
                2 => 'delete_weather_record',
            ),
        );

        $user_permissions = ['User', 'show_user', 'Weather Record', 'show_weather_record'];

        foreach (Role::availableRoles() as $available_role) {
            $role = Role::firstOrCreate([
                'name' => $available_role,
                'guard_name' => 'web',
            ]);
            $this->command->info('Role: ' . $role->name . ' created successfully on ' . Carbon::now()->format('F j, Y, g:i:s A'));
        }

        foreach ($parent_permissions as $parent_permission) {
            $parent = Permission::firstOrCreate([
                'name' => $parent_permission,
                'parent_id' => null,
                'guard_name' => 'web'
            ]);

            $parent->assignRole('Admin');
            if (in_array($parent_permission, $user_permissions)) {
                $parent->assignRole('User');
            }

            if (key_exists($parent_permission, $child_permissions)) {
                foreach ($child_permissions[$parent_permission] as $child_permission) {
                    $perm = Permission::firstOrCreate([
                        'name' => $child_permission,
                        'parent_id' => $parent->id,
                        'guard_name' => 'web'
                    ]);

                    $perm->assignRole('Admin');
                    if (in_array($child_permission, $user_permissions)) {
                        $perm->assignRole('User');
                    }
                }
            }
        }

        $this->command->info('Roles & Permissions were created successfully.');
    }
}