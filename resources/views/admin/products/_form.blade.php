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

    <div class="border rounded-2xl p-5 bg-gray-50">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
            {{ isset($product) ? 'Agregar nuevas imágenes' : 'Imágenes del producto' }}
        </h3>

        <div>
            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                Subir imágenes
            </label>

            <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple
                class="w-full rounded-xl border-gray-300" {{ !isset($product) ? 'required' : '' }}>

            @error('images')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            @error('images.*')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            <p class="text-xs text-gray-500 mt-2">
                Las imágenes se optimizan antes de enviarse para acelerar la subida y mejorar la visualización.
            </p>
        </div>

        @if (!isset($product))
            <input type="hidden" name="featured_image" id="featured_image" value="{{ old('featured_image', 0) }}">
        @endif

        <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden"></div>
    </div>

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

@once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('images');
            const previewGrid = document.getElementById('image-preview-grid');
            const featuredInput = document.getElementById('featured_image');
            const isCreate = !!featuredInput;

            if (!input || !previewGrid) {
                return;
            }

            let filesStore = [];

            input.addEventListener('change', async function(event) {
                const selectedFiles = Array.from(event.target.files || []);

                if (!selectedFiles.length) {
                    return;
                }

                for (const file of selectedFiles) {
                    try {
                        const optimized = await optimizeImage(file);
                        filesStore.push(optimized);
                    } catch (error) {
                        console.error('Error optimizando imagen:', error);
                    }
                }

                syncInputFiles();
                renderPreviews();
            });

            function syncInputFiles() {
                const dt = new DataTransfer();
                filesStore.forEach(file => dt.items.add(file));
                input.files = dt.files;
            }

            function renderPreviews() {
                previewGrid.innerHTML = '';

                if (!filesStore.length) {
                    previewGrid.classList.add('hidden');
                    if (featuredInput) {
                        featuredInput.value = 0;
                    }
                    return;
                }

                previewGrid.classList.remove('hidden');

                filesStore.forEach((file, index) => {
                    const url = URL.createObjectURL(file);

                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative bg-white border rounded-xl p-2';

                    let featuredMarkup = '';
                    if (isCreate) {
                        const checked = Number(featuredInput.value || 0) === index ? 'checked' : '';
                        featuredMarkup = `
                    <label class="flex items-center gap-2 mt-2 text-xs text-gray-700">
                        <input type="radio" name="featured_preview" value="${index}" ${checked}>
                        Imagen de portada
                    </label>
                `;
                    }

                    wrapper.innerHTML = `
                <button
                    type="button"
                    class="remove-preview absolute -top-2 -right-2 bg-red-600 text-white w-7 h-7 rounded-full text-sm font-bold shadow"
                    data-index="${index}"
                >
                    ×
                </button>

                <img src="${url}" class="w-full h-32 object-cover rounded-lg" alt="Preview imagen">

                <div class="mt-2">
                    <p class="text-xs text-gray-500">${formatBytes(file.size)}</p>
                    ${featuredMarkup}
                </div>
            `;

                    previewGrid.appendChild(wrapper);
                });

                previewGrid.querySelectorAll('.remove-preview').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = Number(this.dataset.index);
                        removeFile(index);
                    });
                });

                if (isCreate) {
                    previewGrid.querySelectorAll('input[name="featured_preview"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            featuredInput.value = this.value;
                        });
                    });
                }
            }

            function removeFile(index) {
                filesStore.splice(index, 1);

                if (featuredInput) {
                    let currentFeatured = Number(featuredInput.value || 0);

                    if (currentFeatured === index) {
                        featuredInput.value = 0;
                    } else if (currentFeatured > index) {
                        featuredInput.value = currentFeatured - 1;
                    }
                }

                syncInputFiles();
                renderPreviews();
            }

            function optimizeImage(file) {
                return new Promise((resolve, reject) => {
                    if (!file.type.startsWith('image/')) {
                        reject(new Error('Archivo no es imagen'));
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = new Image();

                        img.onload = function() {
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

                            const originalName = file.name.replace(/\.[^.]+$/, '');

                            canvas.toBlob(function(blob) {
                                if (!blob) {
                                    reject(new Error(
                                        'No se pudo generar la imagen optimizada.'));
                                    return;
                                }

                                resolve(new File(
                                    [blob],
                                    `${originalName}.webp`, {
                                        type: 'image/webp'
                                    }
                                ));
                            }, 'image/webp', 0.82);
                        };

                        img.onerror = reject;
                        img.src = e.target.result;
                    };

                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            }

            function formatBytes(bytes) {
                if (bytes < 1024) return `${bytes} B`;
                if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
                return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
            }
            document.querySelectorAll('form[data-lock-submit="true"]').forEach(form => {
                let submitted = false;

                form.addEventListener('submit', function(event) {
                    if (submitted) {
                        event.preventDefault();
                        return;
                    }

                    submitted = true;

                    const submitButtons = form.querySelectorAll('[data-submit-button]');

                    submitButtons.forEach(button => {
                        button.disabled = true;
                        button.dataset.originalHtml = button.innerHTML;
                        button.innerHTML = button.dataset.loadingText || 'Guardando...';
                    });
                });
            });
        });
    </script>
@endonce
