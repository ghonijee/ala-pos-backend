<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\RequestValidationException;
use Illuminate\Contracts\Validation\Validator;

class StoreRequest extends FormRequest
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
            "phone" => "",
            "address" => "",
            "store_category_id" => "",
            // "store_category_name" => "",
            "address" => "",
            "image_url" => "",
            "image_path" => "",
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new RequestValidationException($validator);
    }
}
