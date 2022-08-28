<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\RequestValidationException;
use Illuminate\Contracts\Validation\Validator;

class ProductRequest extends FormRequest
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
            "store_id" => "required",
            "name" => "required",
            "description" => "",
            "price" => "required",
            "reduce_price" => "",
            "code" => "",
            "cost" => "",
            "use_stock_opname" => "",
            "stock" => "",
            "min_stock" => "",
            "unit" => "",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new RequestValidationException($validator);
    }
}
