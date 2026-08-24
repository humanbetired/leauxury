<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'material',
        'size',
        'description',
        'shopee_url',
        'tokopedia_url',
        'tiktok_shop_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function likes()
    {
        return $this->hasMany(ProductLike::class);
    }

    public function views()
    {
        return $this->hasMany(ProductView::class);
    }

    public function featured()
    {
        return $this->hasOne(FeaturedProduct::class);
    }
}