<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-5xl">

            <div class="bg-yellow-500 text-black rounded-xl px-4 py-3 mb-6">
                <span class="font-bold uppercase tracking-wide text-sm">Modo administrador · Editar producto</span>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <a href="{{ route('admin.products.index') }}"
                        class="text-yellow-600 font-bold uppercase text-sm hover:text-yellow-400 transition">Volver</a>
                    <h1 class="text-2xl font-serif font-bold text-gray-900">Editar producto</h1>
                    <p class="text-sm text-gray-500 mt-1">Actualiza los datos del producto.</p>
                </div>

                <div class="p-6">
                    <form id="product-update-form" method="POST"
                        action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
                        data-lock-submit="true">
                        @method('PUT')
                        @include('admin.products._form')
                    </form>
                </div>
            </div>

            @if ($product->images->count())
                <div class="mt-6 bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-serif font-bold text-gray-900">Imágenes actuales</h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    Marca una imagen destacada y selecciona varias imágenes si deseas eliminarlas de una
                                    vez.
                                </p>
                            </div>

                            <form id="bulk-delete-images-form" method="POST"
                                action="{{ route('admin.products.images.destroy-multiple', $product) }}"
                                onsubmit="return confirm('¿Seguro que deseas eliminar las imágenes seleccionadas?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" id="bulk-delete-button"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-red-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    Eliminar seleccionadas
                                </button>
                            </form>
                        </div>

                        @error('image_ids')
                            <p class="text-sm text-red-600 mt-3">{{ $message }}</p>
                        @enderror

                        @error('image_ids.*')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-6">
                        <div class="mb-4 flex flex-wrap items-center gap-3">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" id="select-all-images"
                                    class="rounded border-gray-300 text-black focus:ring-black">
                                Seleccionar todas
                            </label>

                            <span id="selected-images-count" class="text-sm text-gray-500">
                                0 imágenes seleccionadas
                            </span>
                        </div>

                        <div class="grid md:grid-cols-3 gap-4">
                            @foreach ($product->images as $image)
                                <div class="border rounded-xl p-3">
                                    <img src="{{ asset('storage/' . ($image->thumbnail_path ?: $image->image_path)) }}"
                                        class="w-full h-48 object-cover rounded-lg mb-3" alt="Imagen del producto">

                                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-3">
                                        <input type="radio" name="featured_image_id" value="{{ $image->id }}"
                                            form="product-update-form"
                                            {{ old('featured_image_id', $product->featured_image?->id) == $image->id ? 'checked' : '' }}>
                                        Imagen de portada
                                    </label>

                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="image_ids[]" value="{{ $image->id }}"
                                            form="bulk-delete-images-form"
                                            class="image-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        Seleccionar para eliminar
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @once
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const selectAll = document.getElementById('select-all-images');
                        const checkboxes = Array.from(document.querySelectorAll('.image-checkbox'));
                        const deleteButton = document.getElementById('bulk-delete-button');
                        const counter = document.getElementById('selected-images-count');

                        if (!checkboxes.length || !deleteButton || !counter) {
                            return;
                        }

                        function updateBulkDeleteState() {
                            const selected = checkboxes.filter(cb => cb.checked).length;

                            counter.textContent =
                                `${selected} imagen${selected === 1 ? '' : 'es'} seleccionada${selected === 1 ? '' : 's'}`;
                            deleteButton.disabled = selected === 0;

                            if (selectAll) {
                                selectAll.checked = selected > 0 && selected === checkboxes.length;
                                selectAll.indeterminate = selected > 0 && selected < checkboxes.length;
                            }
                        }

                        if (selectAll) {
                            selectAll.addEventListener('change', function() {
                                checkboxes.forEach(cb => cb.checked = this.checked);
                                updateBulkDeleteState();
                            });
                        }

                        checkboxes.forEach(cb => {
                            cb.addEventListener('change', updateBulkDeleteState);
                        });

                        updateBulkDeleteState();
                    });
                </script>
            @endonce

        </div>
    </section>
</x-store-layout>
