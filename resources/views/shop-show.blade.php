<x-store-layout>
    @php
        $gallery = $product->images
            ->filter(fn($image) => !empty($image->image_path))
            ->values()
            ->map(function ($image) use ($product) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->image_path),
                    'alt' => $product->name,
                    'featured' => (bool) $image->is_featured,
                ];
            })
            ->values();

        if ($gallery->isEmpty() && $product->featured_image && !empty($product->featured_image->image_path)) {
            $gallery = collect([
                [
                    'id' => $product->featured_image->id ?? 0,
                    'url' => asset('storage/' . $product->featured_image->image_path),
                    'alt' => $product->name,
                    'featured' => true,
                ]
            ]);
        }

        $initialIndex = $gallery->search(fn($image) => $image['featured'] === true);
        $initialIndex = $initialIndex === false ? 0 : $initialIndex;

        $phone = '584143284935';
        $message = urlencode("Hola, me interesa este producto: " . $product->name);
    @endphp

    <section class="bg-gray-100 py-12 min-h-screen">
        <div class="container mx-auto px-4 max-w-6xl">

            <div class="mb-8">
                <a href="{{ route('shop') }}" class="text-sm text-gray-500 hover:text-black transition">
                    ← Volver al catálogo
                </a>
            </div>

            <div
                x-data="{
                    images: @js($gallery),
                    currentIndex: {{ $initialIndex }},
                    next() {
                        if (this.images.length <= 1) return;
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    },
                    prev() {
                        if (this.images.length <= 1) return;
                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                    },
                    setImage(index) {
                        this.currentIndex = index;
                    }
                }"
                class="bg-white border rounded-2xl shadow-lg overflow-hidden"
            >
                <div class="grid lg:grid-cols-2 gap-0">

                    {{-- Columna imagen --}}
                    <div class="bg-gray-100 p-6 border-b lg:border-b-0 lg:border-r border-gray-200">
                        <div class="relative rounded-2xl overflow-hidden bg-white border">
                            <template x-if="images.length > 0">
                                <img
                                    :src="images[currentIndex].url"
                                    :alt="images[currentIndex].alt"
                                    class="w-full h-[420px] md:h-[520px] object-cover"
                                >
                            </template>

                            <template x-if="images.length === 0">
                                <div class="w-full h-[420px] md:h-[520px] flex items-center justify-center text-gray-400 text-sm">
                                    Sin imagen
                                </div>
                            </template>

                            <template x-if="images.length > 1">
                                <div>
                                    <button
                                        type="button"
                                        @click="prev()"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-black w-11 h-11 rounded-full shadow flex items-center justify-center transition"
                                        aria-label="Imagen anterior"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        @click="next()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-black w-11 h-11 rounded-full shadow flex items-center justify-center transition"
                                        aria-label="Imagen siguiente"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <template x-if="images.length > 1">
                            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 mt-4">
                                <template x-for="(image, index) in images" :key="image.id">
                                    <button
                                        type="button"
                                        @click="setImage(index)"
                                        class="bg-white border rounded-xl overflow-hidden transition"
                                        :class="currentIndex === index ? 'ring-2 ring-black border-black' : 'hover:border-gray-400'"
                                    >
                                        <img
                                            :src="image.url"
                                            :alt="image.alt"
                                            class="w-full h-20 object-cover"
                                        >
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Columna información --}}
                    <div class="p-8 md:p-10 flex flex-col">
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-widest mb-2">
                                {{ $product->category?->name ?? 'Sin categoría' }}
                                @if($product->brand)
                                    / {{ $product->brand }}
                                @endif
                            </p>

                            <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 leading-tight">
                                {{ $product->name }}
                            </h1>
                        </div>

                        <div class="mb-6">
                            <p class="text-3xl font-bold text-black">
                                {{ $product->price ? '$' . number_format($product->price, 2, ',', '.') : 'Consultar precio' }}
                            </p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 mb-8">
                            @if($product->brand)
                                <div class="bg-gray-50 border rounded-xl p-4">
                                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Marca</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->brand }}</p>
                                </div>
                            @endif

                            @if($product->model)
                                <div class="bg-gray-50 border rounded-xl p-4">
                                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Modelo</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->model }}</p>
                                </div>
                            @endif

                            @if($product->sku)
                                <div class="bg-gray-50 border rounded-xl p-4">
                                    <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">SKU</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->sku }}</p>
                                </div>
                            @endif

                            <div class="bg-gray-50 border rounded-xl p-4">
                                <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Disponibilidad</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $product->is_active ? 'Disponible' : 'No disponible' }}
                                </p>
                            </div>
                        </div>

                        @if($product->description)
                            <div class="mb-8">
                                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-900 mb-3">
                                    Descripción
                                </h2>
                                <div class="bg-gray-50 border rounded-xl p-5">
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto flex flex-wrap gap-3">
                            <a
                                href="https://wa.me/{{ $phone }}?text={{ $message }}"
                                target="_blank"
                                class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest font-bold hover:bg-yellow-500 hover:text-black transition"
                            >
                                Consultar por WhatsApp
                            </a>

                            @auth
                                @if(auth()->user()->is_admin)
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="border border-black text-black px-6 py-3 rounded-lg uppercase text-xs tracking-widest font-bold hover:bg-black hover:text-white transition"
                                    >
                                        Editar producto
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedProducts->count())
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-serif font-bold text-gray-900">Productos relacionados</h2>
                        <a href="{{ route('shop') }}" class="text-sm text-gray-500 hover:text-black transition">
                            Ver catálogo completo
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <div class="bg-white border rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col">
                                <div class="overflow-hidden aspect-square relative bg-gray-100">
                                    @if($related->featured_image)
                                        <img
                                            src="{{ asset('storage/' . $related->featured_image->image_path) }}"
                                            alt="{{ $related->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        >
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                            Sin imagen
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">
                                        {{ $related->category?->name ?? 'Sin categoría' }}
                                    </p>

                                    <h3 class="text-lg font-serif font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $related->name }}
                                    </h3>

                                    <div class="mt-auto">
                                        <p class="text-xl text-black font-bold mb-4">
                                            {{ $related->price ? '$' . number_format($related->price, 2, ',', '.') : 'Consultar' }}
                                        </p>

                                        <a
                                            href="{{ route('shop.show', $related) }}"
                                            class="block w-full text-center border border-black text-black py-2 uppercase text-sm tracking-widest font-bold hover:bg-black hover:text-white transition duration-300"
                                        >
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>
</x-store-layout>