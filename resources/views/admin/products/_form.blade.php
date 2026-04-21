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
            <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand ?? '') }}" required
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black">
        </div>

        <div>
            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                Modelo
            </label>
            <input type="text" id="model" name="model" value="{{ old('model', $product->model ?? '') }}" required
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
                value="{{ old('price', $product->price ?? '') }}" required
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
        <div class="border rounded-2xl p-5 bg-gray-50" x-data="{
            files: [],
            featuredIndex: {{ old('featured_image', 0) }},
            addFiles(event) {
                const selectedFiles = Array.from(event.target.files || []);
        
                if (!selectedFiles.length) return;
        
                const mappedFiles = selectedFiles.map((file, index) => ({
                    id: `${file.name}-${file.size}-${file.lastModified}-${Date.now()}-${index}`,
                    file: file,
                    url: URL.createObjectURL(file),
                }));
        
                this.files = [...this.files, ...mappedFiles];
        
                if (this.featuredIndex >= this.files.length) {
                    this.featuredIndex = 0;
                }
        
                this.syncInput();
            },
            removeFile(index) {
                if (this.files[index] && this.files[index].url) {
                    URL.revokeObjectURL(this.files[index].url);
                }
        
                this.files.splice(index, 1);
        
                if (this.files.length === 0) {
                    this.featuredIndex = 0;
                } else if (this.featuredIndex === index) {
                    this.featuredIndex = 0;
                } else if (this.featuredIndex > index) {
                    this.featuredIndex--;
                }
        
                this.syncInput();
            },
            syncInput() {
                const dataTransfer = new DataTransfer();
        
                this.files.forEach(item => {
                    dataTransfer.items.add(item.file);
                });
        
                this.$refs.images.files = dataTransfer.files;
            }
        }">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                Imágenes del producto
            </h3>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                    Subir imágenes
                </label>

                <input type="file" id="images" name="images[]" x-ref="images" multiple accept="image/*"
                    @click="$event.target.value = null" @change="addFiles($event)"
                    class="w-full rounded-xl border-gray-300" required>

                @error('images')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                @error('images.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="featured_image" :value="featuredIndex">

            <template x-if="files.length > 0">
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-800">Vista previa</h4>
                        <p class="text-xs text-gray-500">Marca una como destacada</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(item, index) in files" :key="item.id">
                            <div class="relative bg-white border rounded-xl p-3 shadow-sm">
                                <button type="button" @click="removeFile(index)"
                                    class="absolute top-2 right-2 z-10 w-8 h-8 rounded-full bg-red-600 text-white font-bold hover:bg-red-500 transition"
                                    title="Quitar imagen">
                                    ×
                                </button>

                                <div class="overflow-hidden rounded-lg border bg-gray-100">
                                    <img :src="item.url" alt="Vista previa" class="w-full h-40 object-cover">
                                </div>

                                <label class="flex items-center gap-2 mt-3 text-sm text-gray-700">
                                    <input type="radio" name="featured_preview" :checked="featuredIndex === index"
                                        @change="featuredIndex = index">
                                    Imagen destacada
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    @else
        <div class="border rounded-2xl p-5 bg-gray-50" x-data="{
            files: [],
            addFiles(event) {
                const selectedFiles = Array.from(event.target.files || []);
        
                if (!selectedFiles.length) return;
        
                const mappedFiles = selectedFiles.map((file, index) => ({
                    id: `${file.name}-${file.size}-${file.lastModified}-${Date.now()}-${index}`,
                    file: file,
                    url: URL.createObjectURL(file),
                }));
        
                this.files = [...this.files, ...mappedFiles];
                this.syncInput();
            },
            removeFile(index) {
                if (this.files[index] && this.files[index].url) {
                    URL.revokeObjectURL(this.files[index].url);
                }
        
                this.files.splice(index, 1);
                this.syncInput();
            },
            syncInput() {
                const dataTransfer = new DataTransfer();
        
                this.files.forEach(item => {
                    dataTransfer.items.add(item.file);
                });
        
                this.$refs.images.files = dataTransfer.files;
            }
        }">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 mb-4">
                Agregar nuevas imágenes
            </h3>

            <input type="file" name="images[]" x-ref="images" multiple accept="image/*"
                @click="$event.target.value = null" @change="addFiles($event)"
                class="w-full rounded-xl border-gray-300">

            @error('images.*')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            <template x-if="files.length > 0">
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Vista previa de nuevas imágenes</h4>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(item, index) in files" :key="item.id">
                            <div class="relative bg-white border rounded-xl p-3 shadow-sm">
                                <button type="button" @click="removeFile(index)"
                                    class="absolute top-2 right-2 z-10 w-8 h-8 rounded-full bg-red-600 text-white font-bold hover:bg-red-500 transition"
                                    title="Quitar imagen">
                                    ×
                                </button>

                                <div class="overflow-hidden rounded-lg border bg-gray-100">
                                    <img :src="item.url" alt="Vista previa" class="w-full h-40 object-cover">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
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
