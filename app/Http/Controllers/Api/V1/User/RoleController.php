<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Role::class)->get();
        return $this->responseData($data)->responseMessage("Role user list")->success();
    }

    public function userRole(Request $request, $id)
    {
        try {
            $user = User::with("role")->find($id);
            throw_if(!$user, new Exception(message: "User not found", code: 400));

            $role = $user->role()->with("permissions")->first();
            return $this->responseData($role)->success();

            //code...
        } catch (ModelNotFoundException $th) {
            return $this->responseMessage($th->getMessage())->failed(404);
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }
}
