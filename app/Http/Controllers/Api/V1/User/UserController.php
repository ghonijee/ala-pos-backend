<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Users\SetupRolePermission;
use Exception;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function userStaff(Request $request, $store)
    {
        try {
            $store = Store::with('users')->findOrFail($store);

            return $this->responseMessage("list user")
                ->responseData($store->users)
                ->success();
        } catch (ModelNotFoundException $th) {
            return $this->responseMessage($th->getMessage())->failed(404);
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }
    public function show(Request $request, $id)
    {
        try {
            $store = User::findOrFail($id);

            return $this->responseMessage("show user")
                ->responseData($store)
                ->success();
        } catch (ModelNotFoundException $th) {
            return $this->responseMessage($th->getMessage())->failed(404);
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }

    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $store = Store::findOrFail($request->store_id);
            $newUserData = $request->only(["fullname", "username", "phone", "email", "password"]);
            $user = User::create($newUserData);

            // Assignment user to store;
            $user->stores()->sync($request->store_id);
            // Setup role and Permission
            SetupRolePermission::fromUserManagement($user, $store, $request->role_id);

            DB::commit();

            return $this->responseMessage("User create success")
                ->responseData($user)
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $query = User::where('id', $id);
            $update = $query->update($data);

            DB::commit();

            return $this->responseMessage("User update success")
                ->responseData($query->first())
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $query = User::where('id', $id);
            $user = $query->first();
            if ($user == null) {
                throw new Exception("User tidak ditemukan", 404);
            }

            if (!Hash::check($request->old_password, $user->password)) {
                throw new Exception("Pasword sebelumnya salah", 419);
            }

            $query->update([
                "password" => bcrypt($request->new_password)
            ]);

            DB::commit();

            return $this->responseMessage("User change password success")
                ->responseData($query->first())
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed($th->getCode());
        }
    }
}
