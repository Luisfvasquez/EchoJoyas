<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'images.array' => 'Debes subir una lista válida de imágenes.',
            'images.*.image' => 'El archivo subido debe ser una imagen.',
            'images.*.mimes' => 'Las imágenes deben estar en formato: jpg, jpeg, png o webp.',
            'images.*.max' => 'Cada imagen no debe pesar más de 8MB.',
            'featured_image.integer' => 'El índice de la imagen destacada debe ser un número entero.',
            'featured_image.min' => 'El índice de la imagen destacada no puede ser negativo.',
        ];

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'featured_image' => ['nullable', 'integer', 'min:0'],
        ], $messages);

        if (! $request->hasFile('images')) {
            return back()
                ->withInput()
                ->with('error', 'Debes seleccionar al menos una imagen válida.');
        }

        if (empty($validated['sku'])) {
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

                $stored = $this->storeOptimizedImage($image);

                ProductImages::create([
                    'product_id' => $product->id,
                    'image_path' => $stored['image_path'],
                    'thumbnail_path' => $stored['thumbnail_path'],
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
            'images.array' => 'Las nuevas imágenes no tienen un formato válido.',
            'images.*.image' => 'El archivo subido debe ser una imagen.',
            'images.*.mimes' => 'Las imágenes deben estar en formato: jpg, jpeg, png o webp.',
            'images.*.max' => 'Cada imagen nueva no debe pesar más de 8MB.',
            'featured_image_id.exists' => 'La imagen destacada seleccionada no es válida.',
        ];

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'featured_image_id' => [
                'nullable',
                Rule::exists('product_images', 'id')->where(fn($query) => $query->where('product_id', $product->id)),
            ],
        ], $messages);

        DB::beginTransaction();

        try {
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

                    $stored = $this->storeOptimizedImage($image);

                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $stored['image_path'],
                        'thumbnail_path' => $stored['thumbnail_path'],
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
            } elseif (! $product->images()->where('is_featured', true)->exists()) {
                $firstImage = $product->images()->first();

                if ($firstImage) {
                    $firstImage->update(['is_featured' => true]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Producto actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error interno al actualizar el producto.');
        }
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete(array_filter([
                $image->image_path,
                $image->thumbnail_path,
            ]));
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function destroyMultipleImages(Request $request, Product $product)
    {
        $validated = $request->validate([
            'image_ids' => ['required', 'array', 'min:1'],
            'image_ids.*' => [
                'integer',
                Rule::exists('product_images', 'id')->where(fn($query) => $query->where('product_id', $product->id)),
            ],
        ], [
            'image_ids.required' => 'Debes seleccionar al menos una imagen.',
            'image_ids.array' => 'El formato de imágenes seleccionadas no es válido.',
            'image_ids.min' => 'Debes seleccionar al menos una imagen.',
            'image_ids.*.exists' => 'Una o más imágenes seleccionadas no pertenecen a este producto.',
        ]);

        DB::beginTransaction();

        try {
            $images = ProductImages::where('product_id', $product->id)
                ->whereIn('id', $validated['image_ids'])
                ->get();

            if ($images->isEmpty()) {
                return back()->with('error', 'No se encontraron imágenes válidas para eliminar.');
            }

            $wasFeaturedDeleted = $images->contains(fn($image) => (bool) $image->is_featured);

            foreach ($images as $image) {
                Storage::disk('public')->delete(array_filter([
                    $image->image_path,
                    $image->thumbnail_path,
                ]));

                $image->delete();
            }

            $remainingImages = ProductImages::where('product_id', $product->id)->get();

            if ($remainingImages->isNotEmpty() && $wasFeaturedDeleted) {
                ProductImages::where('product_id', $product->id)->update(['is_featured' => false]);

                $remainingImages->first()->update(['is_featured' => true]);
            }

            DB::commit();

            return back()->with('success', 'Imágenes eliminadas correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Ocurrió un error al eliminar las imágenes seleccionadas.');
        }
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

    private function storeOptimizedImage(UploadedFile $file): array
    {
        $info = getimagesize($file->getRealPath());

        if (! $info) {
            throw new \RuntimeException('La imagen no es válida.');
        }

        [$width, $height, $type] = $info;

        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG  => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
            default => throw new \RuntimeException('Formato de imagen no soportado.'),
        };

        if (! $source) {
            throw new \RuntimeException('No se pudo procesar la imagen.');
        }

        if ($type === IMAGETYPE_JPEG) {
            $source = $this->applyExifOrientation($file, $source);
            $width = imagesx($source);
            $height = imagesy($source);
        }

        $extension = match ($type) {
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_WEBP => 'webp',
            default        => 'jpg',
        };

        $filename = (string) Str::uuid();

        $imagePath = "products/{$filename}.{$extension}";
        $thumbnailPath = "products/thumbs/{$filename}.{$extension}";

        $this->resizeAndSave(
            source: $source,
            originalWidth: $width,
            originalHeight: $height,
            targetPath: Storage::disk('public')->path($imagePath),
            maxWidth: 1600,
            maxHeight: 1600,
            type: $type
        );

        $this->resizeAndSave(
            source: $source,
            originalWidth: $width,
            originalHeight: $height,
            targetPath: Storage::disk('public')->path($thumbnailPath),
            maxWidth: 500,
            maxHeight: 500,
            type: $type
        );

        imagedestroy($source);

        return [
            'image_path' => $imagePath,
            'thumbnail_path' => $thumbnailPath,
        ];
    }

    private function resizeAndSave(
        $source,
        int $originalWidth,
        int $originalHeight,
        string $targetPath,
        int $maxWidth,
        int $maxHeight,
        int $type
    ): void {
        $ratio = min(
            $maxWidth / $originalWidth,
            $maxHeight / $originalHeight,
            1
        );

        $newWidth = max(1, (int) round($originalWidth * $ratio));
        $newHeight = max(1, (int) round($originalHeight * $ratio));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        } else {
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $white);
        }

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );

        if (! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($canvas, $targetPath, 82),
            IMAGETYPE_PNG  => imagepng($canvas, $targetPath, 7),
            IMAGETYPE_WEBP => imagewebp($canvas, $targetPath, 80),
            default => throw new \RuntimeException('Formato de imagen no soportado.'),
        };

        imagedestroy($canvas);
    }

    private function applyExifOrientation(UploadedFile $file, $image)
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($file->getRealPath());

        if (! $exif || empty($exif['Orientation'])) {
            return $image;
        }

        return match ((int) $exif['Orientation']) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }
}
