<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $viewUsersPermission = Permission::create(['name' => 'view users']);

        $adminRole->givePermissionTo($viewUsersPermission);

        $users = User::whereIn('email', ['sebas@email.com', 'fabi@email.com'])->get();
        foreach ($users as $user) {
            $user->assignRole('admin');
        }

        echo "Seeder completado\n";
    }
}
