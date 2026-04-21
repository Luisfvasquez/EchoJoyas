<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        if ($categories->isEmpty()) {
            return redirect()
                ->route('admin.categories.create')
                ->with('error', 'Primero debes crear al menos una categoría.');
        }

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['required'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:12000'],
            'featured_image' => ['nullable', 'integer', 'min:0'],
        ]);

        if (! $request->hasFile('images')) {
            return back()
                ->withInput()
                ->with('error', 'Debes seleccionar al menos una imagen.');
        }

        if (!$validated['sku']) {
            $validated['sku'] = 'SKU-' . Str::upper(Str::random(8));
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'brand' => $validated['brand'] ?? null,
                'model' => $validated['model'] ?? null,
                'sku' => $validated['sku'] ?? null,
                'price' => $validated['price'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            $files = $request->file('images', []);
            $featuredIndex = (int) $request->input('featured_image', 0);
            $storedCount = 0;

            foreach ($files as $index => $image) {
                if (! $image || ! $image->isValid()) {
                    continue;
                }

                $path = $image->store('products', 'public');

                if (! $path || trim($path) === '') {
                    continue;
                }

                ProductImages::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => $index === $featuredIndex,
                ]);

                $storedCount++;
            }

            if ($storedCount === 0) {
                throw new \RuntimeException('No se pudo guardar ninguna imagen del producto.');
            }

            if (! $product->images()->where('is_featured', true)->exists()) {
                $firstImage = $product->images()->first();

                if ($firstImage) {
                    $firstImage->update(['is_featured' => true]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Producto creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el producto. Revisa que las imágenes sean válidas.');
        }
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'featured_image_id' => ['nullable', 'exists:product_images,id'],
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $product->id),
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['model'] ?? null,
            'sku' => $validated['sku'] ?? null,
            'price' => $validated['price'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images', []) as $image) {
                if (! $image || ! $image->isValid()) {
                    continue;
                }

                $path = $image->store('products', 'public');

                if (! $path || trim($path) === '') {
                    continue;
                }

                ProductImages::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_featured' => false,
                ]);
            }
        }

        if ($request->filled('featured_image_id')) {
            ProductImages::where('product_id', $product->id)->update([
                'is_featured' => false,
            ]);

            ProductImages::where('product_id', $product->id)
                ->where('id', $request->featured_image_id)
                ->update([
                    'is_featured' => true,
                ]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (!empty($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function destroyImage(ProductImages $image)
    {
        $productId = $image->product_id;
        $wasFeatured = $image->is_featured;

        if (!empty($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        if ($wasFeatured) {
            $nextImage = ProductImages::where('product_id', $productId)->first();

            if ($nextImage) {
                $nextImage->update(['is_featured' => true]);
            }
        }

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
