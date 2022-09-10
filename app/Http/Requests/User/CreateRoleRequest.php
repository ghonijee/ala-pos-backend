<?php

namespace App\Http\Requests\User;

use App\Exceptions\RequestValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateRoleRequest extends FormRequest
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
            "name" => "required",
            "store_id" => "required",
            "description" => "",
            "permissions" => "",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new RequestValidationException($validator);
    }
}
