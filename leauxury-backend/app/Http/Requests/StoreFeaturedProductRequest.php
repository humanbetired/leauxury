<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeaturedProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'products' => ['required', 'array', 'max:3'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.position' => ['required', 'integer', 'min:1', 'max:3', 'distinct'],
        ];
    }
}