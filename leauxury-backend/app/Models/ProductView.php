<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'identifier',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}