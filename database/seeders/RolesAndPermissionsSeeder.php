<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Reset cached roles and permissions
          app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

          // create permissions
          Permission::create(['name' => 'edit students']);
          Permission::create(['name' => 'view leaderboard']);
          Permission::create(['name' => 'view staff']);
          Permission::create(['name' => 'create activities']);
          Permission::create(['name' => 'edit users']);

          // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
          app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


          // create roles and assign created permissions

          // this can be done as separate statements

          // or may be done by chaining

          $role = Role::create(['name' => 'System admin']);
          $role->givePermissionTo(Permission::all());

          $role = Role::create(['name' => 'Teacher']);
          $role = Role::create(['name' => 'Admin']);
          $role = Role::create(['name' => 'Superuser']);

          $role = Role::create(['name' => 'SLT'])
          ->givePermissionTo(['view leaderboard']);

                    $user = User::where('email', 'nigelhv@gmail.com')->first();
                    $user->assignRole('System admin');
      }
}
