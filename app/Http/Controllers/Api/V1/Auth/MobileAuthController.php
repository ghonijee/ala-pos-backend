<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class MobileAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validatorRegister($request);

            return $this->responseMessage("Register success")->success(200);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->responseMessage($th->getMessage())->failed(500);
        }
    }

    /**
     * Method for check data validation mobile register
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function validatorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fullname" => "required",
            "username" => "",
            "phone" => "required|numeric",
            "email" => "email",
            "password" => "required",
        ], [
            'required' => 'Data :attribute wajib diisi.',
            'email' => 'Email tidak valid.',
            'numeric' => 'Nomer HP tidak valid.',
        ]);

        // Stop when error exist on first failure
        if ($validator->stopOnFirstFailure()->fails()) {
            // Retrive error message for response to user
            $errorMsg = collect($validator->errors()->getMessages())->flatten()->first();
            // Exception error
            throw new Exception($errorMsg);
        }
    }

    public function login(Request $request)
    {
        //
    }
}
