<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Api\Admin\FeaturedProductController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductLikeController;
use App\Http\Controllers\Api\ProductViewController;
use App\Http\Controllers\Api\Admin\ProductGrowthController;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;


// PUBLIC

Route::get('categories', [CategoryController::class, 'index']);

Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/most-liked', [ProductController::class, 'mostLiked']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::post('products/{product}/like', [ProductLikeController::class, 'store'])->middleware('throttle:10,1');
Route::post('products/{product}/view', [ProductViewController::class, 'store'])->middleware('throttle:30,1');

Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{slug}', [ArticleController::class, 'show']);

// ADMIN

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::apiResource('products', AdminProductController::class)->except(['index', 'show']);
        Route::get('products', [AdminProductController::class, 'index']);
        Route::get('products/{product}', [AdminProductController::class, 'show']);

        Route::post('products/featured', [FeaturedProductController::class, 'store']);

        Route::apiResource('articles', AdminArticleController::class);
        Route::apiResource('categories', AdminCategoryController::class)->except(['show']);
        Route::get('products/{product}/growth', ProductGrowthController::class);
    });
});

