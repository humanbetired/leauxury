<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductLikeController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);

        try {
            $product->likes()->create([
                'identifier' => $request->identifier,
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Anda sudah menyukai produk ini.',
                ], 409);
            }
            throw $e;
        }

        $product->increment('like_count');

        return response()->json([
            'message' => 'Produk disukai.',
            'like_count' => $product->like_count,
        ], 201);
    }
}