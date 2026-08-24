<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'identifier',
    ];

    protected $attributes = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}