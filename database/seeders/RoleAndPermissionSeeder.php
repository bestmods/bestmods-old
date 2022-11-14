<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $fullPerms = array
        (
            'game:add',
            'game:edit',
            'game:delete',

            'mod:view',
            'mod:rate',
            'mod:download',
            'mod:add',
            'mod:edit',
            'mod:delete',

            'seed:add',
            'seed:edit',
            'seed:delete'
        );

        // Create each permission.
        foreach ($fullPerms as $perm)
        {
            Permission::create(['name' => $perm]);
        }

        // Create our roles.
        $admin = Role::create(['name' => 'Admin']);
        $contributor = Role::create(['name' => 'Contributor']);
        $user = Role::create(['name' => 'User']);

        // For spam bots or something.
        $banned = Role::create(['name' => 'Banned']);

        // Assign full permissions to admin.
        $admin->givePermissionTo($fullPerms);

        // Assign permissions to contributor.
        $contributor->givePermissionTo($fullPerms);

        // Assign permissions to user.
        $user->givePermissionTo(['mod:view', 'mod:rate', 'mod:download']);
    }
}
