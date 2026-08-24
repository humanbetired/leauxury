<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'material' => $this->material,
            'size' => $this->size,
            'description' => $this->description,
            'like_count' => $this->like_count,
            'view_count' => $this->view_count,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($img) => asset('storage/' . $img->path));
            }),
            'is_featured' => $this->whenLoaded('featured', fn () => $this->featured !== null),
            'shopee_url' => $this->shopee_url,
            'tokopedia_url' => $this->tokopedia_url,
            'tiktok_shop_url' => $this->tiktok_shop_url,
            'created_at' => $this->created_at,
        ];
    }
}