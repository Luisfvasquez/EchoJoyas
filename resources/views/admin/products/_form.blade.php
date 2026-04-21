@csrf

<div class="space-y-6">
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Categoría
            </label>
            <select id="category_id" name="category_id"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black" required>
                <option value="">Seleccione una categoría</option>
                @foreach ($categories as $categoryOption)
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
            <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black" required>
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
            <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
        </div>

        <div>
            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                Modelo
            </label>
            <input type="text" id="model" name="model" value="{{ old('model', $product->model ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
        </div>

        <div>
            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                SKU
            </label>
            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
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
            <input type="number" step="0.01" id="price" name="price"
                value="{{ old('price', $product->price ?? '') }}"
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
            @error('price')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-end">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                    class="rounded border-gray-300 text-black focus:ring-black"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Producto activo</span>
            </label>
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Descripción
        </label>
        <textarea id="description" name="description" rows="5"
            class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    @if (!isset($product))
        <div class="border rounded-2xl p-5 bg-gray-50">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                Imágenes del producto
            </h3>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                    Subir imágenes
                </label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple
                    class="w-full rounded-xl border-gray-300" required>
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
                <input type="number" id="featured_image" name="featured_image" min="0"
                    value="{{ old('featured_image', 0) }}"
                    class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
                <p class="text-xs text-gray-500 mt-2">
                    La primera imagen es índice 0, la segunda es 1, y así sucesivamente.
                </p>
            </div>
        </div>
    @else
        <div class="border rounded-2xl p-5 bg-gray-50" x-data="imageUploader()">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                {{ isset($product) ? 'Agregar nuevas imágenes' : 'Imágenes del producto' }}
            </h3>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                    Subir imágenes
                </label>

                <input x-ref="input" type="file" id="images" name="images[]"
                    accept="image/jpeg,image/png,image/webp" multiple class="w-full rounded-xl border-gray-300"
                    @change="processFiles($event)" {{ !isset($product) ? 'required' : '' }}>

                @error('images')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                @error('images.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-500 mt-2">
                    Las imágenes se optimizan antes de enviarse para acelerar la subida.
                </p>
            </div>

            @if (!isset($product))
                <input type="hidden" name="featured_image" :value="featuredIndex">
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" x-show="previews.length > 0">
                <template x-for="(preview, index) in previews" :key="preview.id">
                    <div class="relative bg-white border rounded-xl p-2">
                        <button type="button" @click="remove(index)"
                            class="absolute -top-2 -right-2 bg-red-600 text-white w-7 h-7 rounded-full text-sm font-bold shadow">
                            ×
                        </button>

                        <img :src="preview.url" class="w-full h-32 object-cover rounded-lg" alt="Preview">

                        <div class="mt-2">
                            <p class="text-xs text-gray-500" x-text="preview.size"></p>

                            @if (!isset($product))
                                <label class="flex items-center gap-2 mt-2 text-xs text-gray-700">
                                    <input type="radio" name="featured_preview" :checked="featuredIndex === index"
                                        @change="featuredIndex = index">
                                    Imagen de portada
                                </label>
                            @endif
                        </div>
                    </div>
                </template>
            </div>
        </div>

        @once
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('imageUploader', () => ({
                        files: [],
                        previews: [],
                        featuredIndex: 0,

                        async processFiles(event) {
                            const selected = Array.from(event.target.files || []);

                            for (const file of selected) {
                                const optimized = await this.optimize(file);
                                const url = URL.createObjectURL(optimized);

                                this.files.push(optimized);
                                this.previews.push({
                                    id: crypto.randomUUID(),
                                    url,
                                    size: this.formatBytes(optimized.size),
                                });
                            }

                            this.syncInput();
                        },

                        remove(index) {
                            URL.revokeObjectURL(this.previews[index].url);
                            this.previews.splice(index, 1);
                            this.files.splice(index, 1);

                            if (this.featuredIndex >= this.files.length) {
                                this.featuredIndex = 0;
                            }

                            this.syncInput();
                        },

                        syncInput() {
                            const dt = new DataTransfer();
                            this.files.forEach(file => dt.items.add(file));
                            this.$refs.input.files = dt.files;
                        },

                        optimize(file) {
                            return new Promise((resolve, reject) => {
                                const reader = new FileReader();

                                reader.onload = () => {
                                    const img = new Image();

                                    img.onload = () => {
                                        let width = img.width;
                                        let height = img.height;
                                        const maxSize = 1600;

                                        if (width > height && width > maxSize) {
                                            height = Math.round((height * maxSize) / width);
                                            width = maxSize;
                                        } else if (height >= width && height > maxSize) {
                                            width = Math.round((width * maxSize) / height);
                                            height = maxSize;
                                        }

                                        const canvas = document.createElement('canvas');
                                        canvas.width = width;
                                        canvas.height = height;

                                        const ctx = canvas.getContext('2d');
                                        ctx.drawImage(img, 0, 0, width, height);

                                        canvas.toBlob((blob) => {
                                            if (!blob) {
                                                reject(new Error(
                                                    'No se pudo optimizar la imagen.'
                                                    ));
                                                return;
                                            }

                                            const baseName = file.name.replace(
                                                /\.[^.]+$/, '');

                                            resolve(new File(
                                                [blob],
                                                `${baseName}.webp`, {
                                                    type: 'image/webp'
                                                }
                                            ));
                                        }, 'image/webp', 0.82);
                                    };

                                    img.onerror = reject;
                                    img.src = reader.result;
                                };

                                reader.onerror = reject;
                                reader.readAsDataURL(file);
                            });
                        },

                        formatBytes(bytes) {
                            if (bytes < 1024) return `${bytes} B`;
                            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
                            return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
                        }
                    }));
                });
            </script>
        @endonce
    @endif

    <div class="flex flex-wrap gap-3">
        <button type="submit"
            class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition">
            Guardar producto
        </button>

        <a href="{{ route('admin.products.index') }}"
            class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-100 transition">
            Cancelar
        </a>
    </div>
</div>
