<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "product_name" => [
                "required", "string"
            ],
            "product_description" => [
                "required", "string"
            ],
            "product_price_capital" => [
                "required", "integer"
            ],
            "product_price_sell" => [
                "required", "integer"
            ],
        ];
    }
}
