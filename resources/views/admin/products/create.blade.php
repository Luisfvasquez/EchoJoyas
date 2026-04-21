<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-5xl">

            <div class="bg-yellow-500 text-black rounded-xl px-4 py-3 mb-6">
                <span class="font-bold uppercase tracking-wide text-sm">Modo administrador · Nuevo producto</span>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                           <a href="{{ route('admin.products.index') }}" class="text-yellow-600 font-bold uppercase text-sm hover:text-yellow-400 transition">Volver</a>
                    <h1 class="text-2xl font-serif font-bold text-gray-900">Registrar producto</h1>
                    <p class="text-sm text-gray-500 mt-1">Agrega un nuevo producto al catálogo.</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @include('admin.products._form')
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-store-layout>