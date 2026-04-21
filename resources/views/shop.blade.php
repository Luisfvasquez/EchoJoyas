<x-store-layout>
    <div class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4 tracking-wider">Catálogo Exclusivo</h1>
            <p class="text-gray-400 max-w-xl mx-auto text-sm md:text-base">
                Explore nuestra cuidada selección de alta relojería y joyería fina.
            </p>
        </div>
    </div>

    @php
        $selectedCategories = collect(request('categories', []))->map(fn($id) => (int) $id)->all();
    @endphp

    <form method="GET" action="{{ route('shop') }}" class="container mx-auto px-4 py-12 flex flex-col md:flex-row gap-10">

        <aside class="w-full md:w-1/4">
            <div class="sticky top-24 bg-white p-6 border rounded-xl shadow-sm">

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-widest mb-3">Buscar</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ej: Rolex, Oro 18k..."
                        class="w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3 text-sm"
                    >
                </div>

                <div class="mb-8">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest border-b pb-2 mb-4">Categorías</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        @forelse($categories as $category)
                            <li>
                                <label class="flex items-center gap-2 cursor-pointer hover:text-black">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500"
                                    >
                                    {{ $category->name }}
                                    <span class="text-xs text-gray-400">({{ $category->products_count }})</span>
                                </label>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">No hay categorías disponibles.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="mb-8">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest border-b pb-2 mb-4">Rango de Precio</h3>
                    <div class="flex items-center gap-2">
                        <input
                            type="number"
                            name="min_price"
                            value="{{ request('min_price') }}"
                            placeholder="Min $"
                            class="w-1/2 border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3 text-sm"
                        >
                        <span class="text-gray-400">-</span>
                        <input
                            type="number"
                            name="max_price"
                            value="{{ request('max_price') }}"
                            placeholder="Max $"
                            class="w-1/2 border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3 text-sm"
                        >
                    </div>
                </div>

                <div class="space-y-3">
                    <button
                        type="submit"
                        class="w-full bg-black text-white font-bold uppercase tracking-widest py-3 rounded-md hover:bg-yellow-500 hover:text-black transition duration-300 text-sm"
                    >
                        Aplicar Filtros
                    </button>

                    <a
                        href="{{ route('shop') }}"
                        class="block w-full text-center border border-gray-300 text-gray-700 font-bold uppercase tracking-widest py-3 rounded-md hover:bg-gray-100 transition duration-300 text-sm"
                    >
                        Limpiar
                    </a>
                </div>
            </div>
        </aside>

        <main class="w-full md:w-3/4">

            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 border-b pb-4 gap-4">
                <p class="text-gray-600 text-sm">
                    @if($products->total() > 0)
                        Mostrando
                        <span class="font-bold text-black">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                        de
                        <span class="font-bold text-black">{{ $products->total() }}</span>
                        resultados
                    @else
                        No hay resultados para los filtros aplicados
                    @endif
                </p>

                <div class="flex items-center gap-3">
                    <label for="sort" class="text-sm font-bold uppercase tracking-widest text-gray-900">Ordenar por:</label>
                    <select
                        id="sort"
                        name="sort"
                        onchange="this.form.submit()"
                        class="border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3 text-sm"
                    >
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Novedades</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nombre: A - Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nombre: Z - A</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white border rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col relative">
                        @auth
                            @if(auth()->user()->is_admin)
                                <div class="absolute top-2 right-2 z-10 hidden group-hover:block">
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded shadow"
                                    >
                                        Editar
                                    </a>
                                </div>
                            @endif
                        @endauth

                        <div class="overflow-hidden aspect-square relative bg-gray-100">
                            @if($product->featured_image)
                                <img
                                    src="{{ asset('storage/' . $product->featured_image->image_path) }}"
                                    alt="{{ $product->name }}"
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
                                {{ $product->category?->name ?? 'Sin categoría' }}
                                @if($product->brand)
                                    / {{ $product->brand }}
                                @endif
                            </p>

                            <h2 class="text-lg font-serif font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $product->name }}
                            </h2>

                            <div class="mt-auto">
                                <p class="text-xl text-black font-bold mb-4">
                                    {{ $product->price ? '$' . number_format($product->price, 2, ',', '.') : 'Consultar' }}
                                </p>

                                <a
                                    href="{{ route('shop.show', $product) }}"
                                    class="block w-full text-center border border-black text-black py-2 uppercase text-sm tracking-widest font-bold hover:bg-black hover:text-white transition duration-300"
                                >
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border rounded-xl p-10 text-center text-gray-500">
                        No se encontraron productos con los filtros seleccionados.
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>

        </main>
    </form>
</x-store-layout>