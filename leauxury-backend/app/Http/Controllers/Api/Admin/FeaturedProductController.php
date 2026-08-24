<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeaturedProductRequest;
use App\Models\FeaturedProduct;
use Illuminate\Support\Facades\DB;

class FeaturedProductController extends Controller
{
    public function store(StoreFeaturedProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            FeaturedProduct::query()->delete();

            foreach ($request->products as $item) {
                FeaturedProduct::create([
                    'product_id' => $item['product_id'],
                    'position' => $item['position'],
                ]);
            }
        });

        cache()->forget('products.featured');

        return response()->json([
            'message' => 'Featured products updated.',
        ]);
    }
}