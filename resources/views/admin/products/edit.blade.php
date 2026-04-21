<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-5xl">

            <div class="bg-yellow-500 text-black rounded-xl px-4 py-3 mb-6">
                <span class="font-bold uppercase tracking-wide text-sm">Modo administrador · Editar producto</span>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="text-yellow-600 font-bold uppercase text-sm hover:text-yellow-400 transition">Volver</a>
                    <h1 class="text-2xl font-serif font-bold text-gray-900">Editar producto</h1>
                    <p class="text-sm text-gray-500 mt-1">Actualiza los datos del producto.</p>
                </div>

                <div class="p-6">
                    <form
                        id="product-update-form"
                        method="POST"
                        action="{{ route('admin.products.update', $product) }}"
                        enctype="multipart/form-data"
                    >
                        @method('PUT')
                        @include('admin.products._form')
                    </form>
                </div>
            </div>

            @if($product->images->count())
                <div class="mt-6 bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-xl font-serif font-bold text-gray-900">Imágenes actuales</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Marca una imagen destacada y luego pulsa “Guardar producto”. Eliminar imagen es una acción independiente.
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid md:grid-cols-3 gap-4">
                            @foreach($product->images as $image)
                                <div class="border rounded-xl p-3">
                                    <img
                                        src="{{ asset('storage/' . $image->image_path) }}"
                                        class="w-full h-48 object-cover rounded-lg mb-3"
                                        alt="Imagen del producto"
                                    >

                                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-3">
                                        <input
                                            type="radio"
                                            name="featured_image_id"
                                            value="{{ $image->id }}"
                                            form="product-update-form"
                                            {{ old('featured_image_id', $product->featured_image?->id) == $image->id ? 'checked' : '' }}
                                        >
                                        Imagen de portada
                                    </label>

                                    <form method="POST"
                                          action="{{ route('admin.products.images.destroy', $image) }}"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar esta imagen?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-red-500 transition">
                                            Eliminar imagen
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
</x-store-layout>