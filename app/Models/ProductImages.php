<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $fillable = ['product_id', 'image_path', 'is_featured','thumbnail_path'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
