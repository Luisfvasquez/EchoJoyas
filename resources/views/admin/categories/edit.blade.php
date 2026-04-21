<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-3xl">

            <div class="bg-yellow-500 text-black rounded-xl px-4 py-3 mb-6">
                <span class="font-bold uppercase tracking-wide text-sm">Modo administrador · Editar categoría</span>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                           <a href="{{ route('admin.categories.index') }}" class="text-yellow-600 font-bold uppercase text-sm hover:text-yellow-400 transition">Volver</a>
                    <h1 class="text-2xl font-serif font-bold text-gray-900">Editar categoría</h1>
                    <p class="text-sm text-gray-500 mt-1">Actualiza la información de esta categoría.</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @method('PUT')
                        @include('admin.categories._form')
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-store-layout>