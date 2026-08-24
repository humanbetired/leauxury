<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $frontendUrl = config('app.frontend_url');

        $urls = collect([
            ['loc' => $frontendUrl, 'lastmod' => now()->toDateString(), 'priority' => '1.0'],
            ['loc' => "{$frontendUrl}/products", 'lastmod' => now()->toDateString(), 'priority' => '0.9'],
            ['loc' => "{$frontendUrl}/gallery", 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => "{$frontendUrl}/articles", 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => "{$frontendUrl}/contact", 'lastmod' => now()->toDateString(), 'priority' => '0.5'],
        ]);

        Product::select('id', 'updated_at')->each(function ($product) use ($urls, $frontendUrl) {
            $urls->push([
                'loc' => "{$frontendUrl}/products/{$product->id}",
                'lastmod' => $product->updated_at->toDateString(),
                'priority' => '0.7',
            ]);
        });

        Article::where('status', 'published')->select('slug', 'updated_at')->each(function ($article) use ($urls, $frontendUrl) {
            $urls->push([
                'loc' => "{$frontendUrl}/artikel/{$article->slug}",
                'lastmod' => $article->updated_at->toDateString(),
                'priority' => '0.6',
            ]);
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "        <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "        <priority>{$url['priority']}</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}