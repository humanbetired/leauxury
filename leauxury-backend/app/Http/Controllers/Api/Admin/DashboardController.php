<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_products' => Product::count(),
            'total_articles' => Article::count(),
            'total_likes' => Product::sum('like_count'),
            'total_views' => Product::sum('view_count'),
            'most_liked_product' => Product::orderByDesc('like_count')->first(['id', 'title', 'like_count']),
            'most_viewed_product' => Product::orderByDesc('view_count')->first(['id', 'title', 'view_count']),
            'top_5_liked_products' => Product::orderByDesc('like_count')
                ->take(5)
                ->get(['id', 'title', 'like_count', 'view_count']),
            'top_5_viewed_products' => Product::orderByDesc('view_count')
                ->take(5)
                ->get(['id', 'title', 'like_count', 'view_count']),
        ]);
    }
}