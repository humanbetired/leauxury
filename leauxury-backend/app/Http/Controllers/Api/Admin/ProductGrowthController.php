<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductGrowthController extends Controller
{
    public function __invoke(Request $request, Product $product)
    {
        $days = (int) $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $likes = $product->likes()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $views = $product->views()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $likeData = [];
        $viewData = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = $date;
            $likeData[] = $likes->get($date, 0);
            $viewData[] = $views->get($date, 0);
        }

        return response()->json([
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
            ],
            'labels' => $labels,
            'likes' => $likeData,
            'views' => $viewData,
        ]);
    }
}