<div class="bg-white p-8 rounded-lg shadow-lg border">
    <h2 class="text-2xl font-serif font-bold mb-6">Registrar Nueva Pieza</h2>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Descripción de Lujo</label>
            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Detalles de movimiento, calibre, materiales..."></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="brand" placeholder="Ej: Hublot" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Precio ($)</label>
                <input type="number" name="price" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Principal</label>
                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-black text-white px-8 py-3 rounded-md font-bold uppercase tracking-widest hover:bg-yellow-600 transition">
                Publicar en Tienda
            </button>
        </div>
    </form>
</div>