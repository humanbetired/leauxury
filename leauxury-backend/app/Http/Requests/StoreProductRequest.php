<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'size' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'images' => ['required', 'array', 'min:1'], 
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=200,min_height=200,max_width=4000,max_height=4000'],
            'shopee_url' => ['nullable', 'url', 'max:500'],
            'tokopedia_url' => ['nullable', 'url', 'max:500'],
            'tiktok_shop_url' => ['nullable', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.dimensions' => 'Ukuran gambar terlalu besar, maksimal 4000x4000 piksel.',
            'images.*.max' => 'Ukuran file gambar maksimal 2MB.',
            'images.*.mimes' => 'Format gambar harus JPG, PNG, atau WEBP.',
        ];
    }
}