<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductViewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $product->views()->create([
            'identifier' => $request->input('identifier'),
        ]);

        $product->increment('view_count');

        return response()->json([
            'view_count' => $product->view_count,
        ], 201);
    }
}