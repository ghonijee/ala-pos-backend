<?php

namespace App\Actions\Users;

use App\Constant\UserDefaultRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Exception;

class SetupRolePermission
{
    private User $user;

    private Store $store;

    /**
     * Setup Default User Management access for new User Registered.
     * this action will generate new default role for new store (Owner & Staff)
     * then assignment permission and finaly assignment owner role to new user
     */
    public static function fromRegister(User $user, Store $store)
    {
        try {
            // initial
            $instance = new self($user, $store);
            // Create role Default to Store
            $instance->createRole();
            // Assignment Default Permission to Role
            $instance->assigmnetPermission();
            // Assignment Role Owner to new User
            $instance->assignmentUserRole(UserDefaultRole::OWNER);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function __construct(User $user, Store $store)
    {
        $this->user = $user;
        $this->store = $store;
    }

    public function assignmentUserRole($role)
    {
        $role = $this->store->roles()->where("name", $role);
        $this->user->roles()->async($role->id);
    }

    /**
     * Create default role to store model
     */
    public function createRole(): void
    {
        // Setup Default role
        $roles = [
            [
                "name" => UserDefaultRole::OWNER,
                "description" => "Pemilik usaha dengan akses semua fitur",
                "store_id" => $this->store->id,
            ], [
                "name" => UserDefaultRole::STAFF,
                "description" => "Pegawai toko/usaha",
                "store_id" => $this->store->id,
            ]
        ];

        // Create role to Store by relation
        $this->store->roles()->createMany($roles);
    }

    /**
     * Assignment permission tol default Role
     */
    public function assigmnetPermission()
    {
        // get role by store
        $roles = $this->store->roles();

        $roles->each(function ($role, $key) {
            switch ($role->name) {
                case UserDefaultRole::OWNER:
                    $role->permissions()->sync($this->ownerDefaultPermission());
                    break;
                case UserDefaultRole::STAFF:
                    $role->permissions()->sync($this->staffDefaultPermission());
                    break;
                default:
                    throw new Exception("Role not found");
                    break;
            }
        });
    }

    /**
     * Get ID default permission for staff role
     */
    private function staffDefaultPermission()
    {
        $permissionKey =  [
            "create-transaction",
            "show-transaction",
            "cancel-transaction",
            "delete-transaction",
            "create-product",
            "show-product",
            "update-product",
            "delete-product",
        ];

        $permissionId = Permission::whereIn('key', $permissionKey)->get('id');
        return $permissionId->pluck('id');
    }

    /**
     * Get ID default permission for owner role
     */
    private function ownerDefaultPermission()
    {
        $permissionId = Permission::all('id');
        return $permissionId->pluck('id');
    }
}

/**
 * Step
 * 1. Create Role For Store
 * 2. Assignment New User from register to Owner Role
 * 3. Assigmnet Permission to Role
 */
