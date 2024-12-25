<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class Rpcp extends Component
{
    public $isDisabled = true;
    public $role_name, $permission_name, $role_to_delete, $role;
    public $roles, $permissions, $assoc_role, $assoc_permission, $role_id;
    public $roleSearch1, $roleSearch2, $permissionSearch1, $permissionSearch2;
    public $type;

    protected $rules = [
        'role_name' => 'required|max:12|unique:roles,name',
        'permission_name' => 'required|max:20|unique:permissions,name',
    ];

    public function delete_role($id)
    {

        $role = Role::find($id);
        $role->delete();
    }
    public function delete_permission($id)
    {

        $permission = Permission::find($id);
        $permission->delete();
    }
    public function add_role()
    {

        Role::create(['name' => $this->role_name]);
    }


    public function add_permission()
    {

        Permission::create(['name' => $this->permission_name]);
    }

    public function associate()
    {
        $role = Role::where('name', $this->assoc_role)->first();
        $permission = Permission::where('name', $this->assoc_permission)->first();

        $role->givePermissionTo($permission);
    }

    public function dissociate($role_name, $permission_name)
    {

        $role = Role::findByName($role_name);

        $role->revokePermissionTo($permission_name);
    }



    public function mount()
    {

        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $this->roles = Role::where('name', 'like', '%' . $this->roleSearch2 . '%')->get();
        $this->permissions = Permission::where('name', 'like', '%' . $this->permissionSearch2 . '%')->get();
        return view('livewire.rpcp', [
            'roles' => $this->readRoles(),
            'permissions' => $this->readPermissions(),
        ]);
    }
    public  function readRoles()
    {
        $roles = Role::all();
        return $roles;
    }
    public  function readPermissions()
    {
        $permissions = Permission::all();
        return $permissions;
    }
}
