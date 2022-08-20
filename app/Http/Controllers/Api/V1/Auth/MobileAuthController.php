<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Constant\UserStatus;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator as Validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    /**
     * Method for store data new user
     * if register success method will generate token
     * and user set Login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();

            if (!$request->has('fullname')) {
                $request->merge([
                    "fullname" => $request->username
                ]);
            }

            $request->merge([
                "user_status" => UserStatus::FREE,
            ]);

            $validator = $this->validatorRegister($request);

            $user = User::create($validator->validated());

            $token = $this->generateToken($user, $request->device_name);

            DB::commit();

            return $this->responseMessage("Register success")->responseData([
                "personal_access_token" => $token,
                "user" => $user
            ])->success(200);
        } catch (Exception $e) {
            DB::rollback();

            return $this->responseMessage($e->getMessage())->failed(422);
        }
    }

    /**
     * User mobile login can use the username or phone
     * for login apps
     *
     * @param \Illuminate\Http\Request $request
     *
     */
    public function login(Request $request)
    {
        try {
            $loginField = $this->getLoginType($request->username);

            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            $user = User::where($loginField, $request->username)->with("role")->first();

            if (!$user) {
                throw new Exception("Data user belum tersedia, silahkan daftar terlebih dulu");
            }

            if (!Hash::check($request->password, $user->password)) {
                throw new Exception("Password salah, coba lagi!");
            }

            // Permission single login check by config env
            if (config("app.single_login")) {
                // Clear all token on DB
                $user->tokens()->delete();
            }

            return $this->responseMessage("Login success")->responseData([
                "personal_access_token" => $this->generateToken($user, $request->device_name),
                "user" => $user,
            ])->success(200);
        } catch (ValidationException $th) {
            return $this->responseMessage($th->getMessage())->failed(422);
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->failed(401);
        }
    }

    public function checkToken(Request $request)
    {
        try {
            $user = Auth::user();

            return $this->responseData($user)->responseMessage("Token is valid")->success();
        } catch (\Throwable $th) {
            return $this->responseMessage("Token invalid")->failed();
        }
    }

    /**
     * Auth logout user, this request will delete token on Database
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->responseMessage("Logout success")->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    /**
     * Generate token from Sanctum for user login
     *
     * @param \App\Models\User $user user data
     * @param String $deviceName device name from client
     */
    private function generateToken($user, $deviceName = null): String
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * Method for check data validation mobile register
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fullname" => "",
            "user_status" => "",
            "username" => "required",
            "phone" => "required|numeric",
            "email" => "email",
            "password" => "required",
            "device_name" => "required",
        ], [
            'required' => 'Data :attribute wajib diisi.',
            'email' => 'Email tidak valid.',
            'numeric' => 'Nomer HP tidak valid.',
            "device_name" => "Nama device tidak valid",
        ]);

        // Stop when error exist on first failure
        if ($validator->fails()) {
            // Retrive error message for response to user
            $errorMsg = collect($validator->errors()->getMessages())->flatten()->first();
            // Exception error
            throw new Exception($errorMsg);
        }

        return $validator;
    }

    /**
     * Get type username login type
     * Email, Phone, or Username
     * @param String $data
     *
     * @return String
     */
    private function getLoginType($data): String
    {
        $loginType = "Undefined";
        switch (true) {
            case filter_var($data, FILTER_VALIDATE_EMAIL):
                $loginType = "email";
                break;
            case preg_match("/^[0-9]{10,13}+$/", $data):
                $loginType = "phone";
                break;
            default:
                $loginType = "username";
                break;
        }
        return $loginType;
    }
}
