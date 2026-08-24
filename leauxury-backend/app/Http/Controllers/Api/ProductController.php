<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\FeaturedProduct;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return ProductResource::collection($query->latest()->paginate(12));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'images']));
    }

    public function featured()
    {
        $data = cache()->remember('products.featured', 300, function () {
            $products = FeaturedProduct::with('product.category', 'product.images')
                ->orderBy('position')
                ->get()
                ->pluck('product');

            return ProductResource::collection($products)->response()->getData(true);
        });

        return response()->json($data);
    }

    public function mostLiked()
    {
        $products = Product::with(['category', 'images'])
            ->orderByDesc('like_count')
            ->take(3)
            ->get();

        return ProductResource::collection($products);
    }
}