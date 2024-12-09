<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Going to create the following roles:
     * 1 - system admin
     * 2 - super user
     * 3 - teacher
     * 4 - slt
     * 5 - admin
     */
    public function run(): void
    {
                // Reset cached roles and permissions
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // create permissions
                Permission::create(['name' => 'create students']);
                Permission::create(['name' => 'create users']);
                Permission::create(['name' => 'edit students']);
                Permission::create(['name' => 'edit users']);
                Permission::create(['name' => 'view admin menu']);

                // create roles and assign existing permissions


                $role1 = Role::create(['name' => 'System admin']);
                $role1->givePermissionTo('create users');
                $role1->givePermissionTo('edit users');
                $role1->givePermissionTo('view admin menu');

                $role2 = Role::create(['name' => 'Super user']);
                $role2->givePermissionTo('edit students');
                $role2->givePermissionTo('create students');

                $role3 = Role::create(['name' => 'teacher']);
                $role3->givePermissionTo('edit students');
                $role3->givePermissionTo('create students');

                $role4 = Role::create(['name' => 'SLT']);
                $role4->givePermissionTo('edit students');
                $role4->givePermissionTo('create students');

                $role5 = Role::create(['name' => 'admin']);
                $role5->givePermissionTo('edit students');
                $role5->givePermissionTo('create students');

                // gets all permissions via Gate::before rule; see AuthServiceProvider

                // create demo users
          //       $user = \App\Models\User::factory()->create([
          //           'surname' => 'User',
          //           'first_name' => 'Example',
          //           'email' => 'test@example.com',
          //       ]);
          //       $user->assignRole($role1);

          //       $user = \App\Models\User::factory()->create([
          //           'surname' => 'Admin User',
          //           'first_name' => 'Example',
          //           'email' => 'admin@example.com',
          //       ]);
          //       $user->assignRole($role2);

          //       $user = \App\Models\User::factory()->create([
          //           'surname' => 'Hagger-Vaughan',
          //           'first_name' => 'Nigel',
          //           'email' => 'nigelhv@gmail.com',
          //           'location_id' => '1',
          //           'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
          //       ]);
                $user->assignRole($role1);
            }
    }
