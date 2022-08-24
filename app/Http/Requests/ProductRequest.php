<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
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
}
