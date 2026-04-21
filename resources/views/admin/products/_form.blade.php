@csrf

<div class="space-y-6">
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Categoría
            </label>
            <select
                id="category_id"
                name="category_id"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                required
            >
                <option value="">Seleccione una categoría</option>
                @foreach($categories as $categoryOption)
                    <option value="{{ $categoryOption->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $categoryOption->id ? 'selected' : '' }}>
                        {{ $categoryOption->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nombre
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $product->name ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                required
            >
            @error('name')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div>
            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                Marca
            </label>
            <input
                type="text"
                id="brand"
                name="brand"
                value="{{ old('brand', $product->brand ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
            >
        </div>

        <div>
            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                Modelo
            </label>
            <input
                type="text"
                id="model"
                name="model"
                value="{{ old('model', $product->model ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
            >
        </div>

        <div>
            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                SKU
            </label>
            <input
                type="text"
                id="sku"
                name="sku"
                value="{{ old('sku', $product->sku ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
            >
            @error('sku')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                Precio
            </label>
            <input
                type="number"
                step="0.01"
                id="price"
                name="price"
                value="{{ old('price', $product->price ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
            >
            @error('price')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-end">
            <label class="inline-flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="rounded border-gray-300 text-black focus:ring-black"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                >
                <span class="text-sm font-medium text-gray-700">Producto activo</span>
            </label>
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Descripción
        </label>
        <textarea
            id="description"
            name="description"
            rows="5"
            class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    @if(!isset($product))
        <div class="border rounded-2xl p-5 bg-gray-50">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                Imágenes del producto
            </h3>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                    Subir imágenes
                </label>
                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept="image/*"
                    multiple
                    class="w-full rounded-xl border-gray-300"
                    required
                >
                @error('images')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Índice de imagen destacada
                </label>
                <input
                    type="number"
                    id="featured_image"
                    name="featured_image"
                    min="0"
                    value="{{ old('featured_image', 0) }}"
                    class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                >
                <p class="text-xs text-gray-500 mt-2">
                    La primera imagen es índice 0, la segunda es 1, y así sucesivamente.
                </p>
            </div>
        </div>
    @else
        <div class="border rounded-2xl p-5 bg-gray-50">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                Agregar nuevas imágenes
            </h3>

            <input
                type="file"
                name="images[]"
                multiple
                class="w-full rounded-xl border-gray-300"
            >

            @error('images.*')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div class="flex flex-wrap gap-3">
        <button
            type="submit"
            class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition"
        >
            Guardar producto
        </button>

        <a href="{{ route('admin.products.index') }}"
           class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-100 transition">
            Cancelar
        </a>
    </div>
</div>