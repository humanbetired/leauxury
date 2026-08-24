<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images', 'featured'])
            ->latest()
            ->paginate(10);

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->only([
            'category_id', 'title', 'material', 'size', 'description',
            'shopee_url', 'tokopedia_url', 'tiktok_shop_url',
        ]));

        $manager = ImageManager::usingDriver(Driver::class);

        foreach ($request->file('images', []) as $image) {
            $img = $manager->decode($image);
            $img->scaleDown(width: 800);

            $filename = 'products/' . uniqid() . '.webp';
            Storage::disk('public')->put($filename, (string) $img->encodeUsingFormat(Format::WEBP, quality: 75));

            $product->images()->create(['path' => $filename]);
        }

        return new ProductResource($product->load(['category', 'images']));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'images', 'featured']));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->only([
            'category_id', 'title', 'material', 'size', 'description',
            'shopee_url', 'tokopedia_url', 'tiktok_shop_url',
        ]));

        $manager = ImageManager::usingDriver(Driver::class);

        foreach ($request->file('images', []) as $image) {
            $img = $manager->decode($image);
            $img->scaleDown(width: 800);

            $filename = 'products/' . uniqid() . '.webp';
            Storage::disk('public')->put($filename, (string) $img->encodeUsingFormat(Format::WEBP, quality: 75));

            $product->images()->create(['path' => $filename]);
        }

        return new ProductResource($product->load(['category', 'images']));
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}