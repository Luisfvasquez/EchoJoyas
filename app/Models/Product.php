<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'model',
        'sku',
        'price',
        'description',
        'is_active'
    ];

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFeaturedImageAttribute()
    {
        return $this->images()->where('is_featured', true)->first()
            ?? $this->images()->first();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
