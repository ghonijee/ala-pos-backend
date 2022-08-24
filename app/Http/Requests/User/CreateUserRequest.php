<?php

namespace App\Http\Requests\User;

use App\Exceptions\RequestValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "fullname" => "required",
            "username" => "required",
            "email" => "",
            "phone" => "required",
            "password" => "required",
            "store_id" => "required",
            "role_id" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new RequestValidationException($validator);
    }
}
