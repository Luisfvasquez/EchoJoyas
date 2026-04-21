@csrf

<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Nombre de la categoría
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $category->name ?? '') }}"
            class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
            placeholder="Ej: Relojes, Anillos, Pulseras"
            required
        >
        @error('name')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div>
        @error('description')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button
            type="submit"
            class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition"
        >
            Guardar categoría
        </button>

        <a href="{{ route('admin.categories.index') }}"
           class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-100 transition">
            Cancelar
        </a>
    </div>
</div>