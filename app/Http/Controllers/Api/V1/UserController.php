<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

            dd($th);

            return $this->responseMessage($th->getMessage())->failed();
        }
    }
}
