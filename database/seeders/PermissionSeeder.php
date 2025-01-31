<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Define roles
        $roles = ['Admin', 'Acquisition', 'BRM'];

        // Define models and their permissions
        $models = ['Acquisition', 'Contact', 'Event', 'Feedback', 'Job', 'Location', 'Media', 'Milestone', 'TeamMember'];
        $permissions = ['add', 'edit', 'view', 'delete'];

        // Create permissions for each model
        foreach ($models as $model) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => "$permission $model"]);
            }
        }

        // Create roles and assign permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($roleName === 'Admin') {
                // Admin gets all permissions
                $role->syncPermissions(Permission::all());
            } else {
                // Other roles get limited permissions (customize as needed)
                foreach ($models as $model) {
                    $role->givePermissionTo("view $model");
                }
            }
        }
    }
}
