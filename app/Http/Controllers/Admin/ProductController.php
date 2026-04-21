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
        // 1. Definimos los mensajes personalizados
        $messages = [
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'brand.max' => 'La marca no puede superar los 255 caracteres.',
            'model.max' => 'El modelo no puede superar los 255 caracteres.',
            'sku.unique' => 'Este SKU ya está registrado en otro producto.',
            'sku.max' => 'El SKU no puede superar los 255 caracteres.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',
            'images.required' => 'Debes subir al menos una imagen para el producto.',
            'images.*.image' => 'El archivo subido debe ser una imagen.',
            'images.*.mimes' => 'Las imágenes deben estar en formato: jpg, jpeg, png o webp.',
            'images.*.max' => 'Cada imagen no debe pesar más de 12MB.',
            'featured_image.integer' => 'El índice de la imagen destacada debe ser un número entero.',
            'featured_image.min' => 'El índice de la imagen destacada no puede ser negativo.',
        ];

        // 2. Pasamos los mensajes como segundo parámetro
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
        ], $messages);

        if (! $request->hasFile('images')) {
            return back()
                ->withInput()
                ->with('error', 'Debes seleccionar al menos una imagen válida.');
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
                throw new \RuntimeException('No se pudo guardar ninguna imagen del producto en el servidor.');
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
                ->with('error', 'Ocurrió un error interno al guardar el producto. Intenta nuevamente.');
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
        // 1. Mensajes de validación para la actualización
        $messages = [
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'brand.max' => 'La marca no puede superar los 255 caracteres.',
            'model.max' => 'El modelo no puede superar los 255 caracteres.',
            'sku.unique' => 'Este SKU ya está registrado en otro producto.',
            'sku.max' => 'El SKU no puede superar los 255 caracteres.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',
            'images.*.image' => 'El archivo subido debe ser una imagen.',
            'images.*.mimes' => 'Las imágenes deben estar en formato: jpg, jpeg, png o webp.',
            'images.*.max' => 'Cada imagen nueva no debe pesar más de 4MB.',
            'featured_image_id.exists' => 'La imagen destacada seleccionada no es válida.',
        ];

        // 2. Pasamos los mensajes
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
        ], $messages);

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