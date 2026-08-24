<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::withCount('products')->latest()->get());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => Category::generateUniqueSlug($request->name),
        ]);

        return new CategoryResource($category);
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = ['name' => $request->name];

        if ($request->name !== $category->name) {
            $data['slug'] = Category::generateUniqueSlug($request->name, $category->id);
        }

        $category->update($data);

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            throw ValidationException::withMessages([
                'category' => ['Kategori tidak bisa dihapus karena masih memiliki produk.'],
            ]);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}