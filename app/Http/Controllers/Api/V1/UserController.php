<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function update(UserRequest $request, $id)
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
