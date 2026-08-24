<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000'],
            'status' => ['required', 'in:draft,published'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=200,min_height=200,max_width=4000,max_height=4000'],
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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }
}