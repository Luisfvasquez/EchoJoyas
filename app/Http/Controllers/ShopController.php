<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->orderBy('name')->get();

        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categories')) {
            $categoryIds = collect($request->categories)
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('min_price')) {
            $query->whereNotNull('price')
                ->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->whereNotNull('price')
                ->where('price', '<=', $request->max_price);
        }

        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }

        $product->load(['category', 'images']);

        $relatedProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('shop-show', compact('product', 'relatedProducts'));
    }
}