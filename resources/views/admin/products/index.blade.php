<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-7xl">

            <div class="bg-yellow-500 text-black rounded-xl px-4 py-3 mb-6 flex flex-wrap gap-2 items-center justify-between">
                <div class="font-bold uppercase tracking-wide text-sm">
                    Modo administrador · Gestión de productos
                </div>

                <div class="flex flex-wrap gap-2 text-xs md:text-sm">
                    <a href="{{ route('admin.products.index') }}" class="bg-black text-white px-4 py-2 rounded-full">
                        Productos
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="bg-white text-black px-4 py-2 rounded-full">
                        Nuevo producto
                    </a>
                </div>
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
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-serif font-bold text-gray-900">Productos</h1>
                        <p class="text-sm text-gray-500 mt-1">Administra el catálogo de la tienda.</p>
                    </div>

                    <a href="{{ route('admin.products.create') }}"
                       class="bg-black text-white px-5 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition">
                        Agregar producto
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                                <th class="px-6 py-4">Imagen</th>
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Categoría</th>
                                <th class="px-6 py-4">Marca</th>
                                <th class="px-6 py-4">SKU</th>
                                <th class="px-6 py-4">Precio</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-t border-gray-100">
                                    <td class="px-6 py-4">
                                        @if($product->featured_image)
                                            <img
                                                src="{{ asset('storage/' . $product->featured_image->image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="w-16 h-16 object-cover rounded-lg border"
                                            >
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400">
                                                Sin imagen
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->category?->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->brand ?: '—' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->sku ?: '—' }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $product->price ? '$' . number_format($product->price, 2, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->is_active)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Activo
                                            </span>
                                        @else
                                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="bg-yellow-500 text-black px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-yellow-400 transition">
                                                Editar
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.products.destroy', $product) }}"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-red-500 transition">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                        No hay productos registrados todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
</x-store-layout>