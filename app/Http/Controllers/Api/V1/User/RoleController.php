<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRoleRequest;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    /**
     * Get All role with QueryAdapter for query builder
     */
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Role::class)->get();
        return $this->responseData($data)->responseMessage("Role user list")->success();
    }

    /**
     * Find role and permission by User ID
     * @param Request $request
     * @param int $id
     */
    public function userRole(Request $request, $id)
    {
        try {
            $user = User::with("role")->find($id);
            throw_if(!$user, new Exception(message: "User not found", code: 400));

            $role = $user->role()->with("permissions")->first();
            return $this->responseData($role)->success();
        } catch (ModelNotFoundException $th) {
            return $this->responseMessage($th->getMessage())->failed(404);
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }

    /** 
     * Create new Role and assignment permission to Store
    */
    public function store(CreateRoleRequest $request)
    {
        try {
            $role = Role::create($request->only("name", "store_id", "description"));

            // Decode and assignment permissions to role
            $permissionId = json_decode($request->permissions);
            $role->permissions()->sync($permissionId);

            return $this->responseData($role)->success();
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }
}
