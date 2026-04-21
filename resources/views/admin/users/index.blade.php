<x-store-layout>
    <section class="bg-gray-100 min-h-screen py-10">
        <div
            class="container mx-auto px-4 max-w-6xl"
            x-data="{ createOpen: {{ $errors->any() && old('_modal') === 'create' ? 'true' : 'false' }} }"
        >
            <div class="bg-yellow-500 text-black rounded-xl px-3 py-2 sm:px-4 sm:py-3 mb-6 flex flex-wrap items-center justify-between gap-3">
                <span class="font-bold uppercase tracking-wide text-xs sm:text-sm">Modo administrador · Gestión de usuarios</span>

                <button
                    type="button"
                    @click="createOpen = true"
                    class="bg-black text-white px-3 py-1 sm:px-5 sm:py-1 sm:px-5 sm:py-1 sm:px-5 sm:py-2 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition"
                >
                    Nuevo usuario
                </button>
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
                    <h1 class="text-xl sm:text-2xl font-serif font-bold text-gray-900">Usuarios</h1>
                    <p class="text-sm text-gray-500 mt-1">Administra los accesos del sistema.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Correo</th>
                                <th class="px-6 py-4">Rol</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-t border-gray-100">
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->is_admin)
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                Administrador
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Usuario
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div
                                            class="flex justify-end gap-2"
                                            x-data="{ editOpen: {{ $errors->any() && old('_modal') === 'edit' && (int) old('_edit_id') === $user->id ? 'true' : 'false' }} }"
                                        >
                                            <button
                                                type="button"
                                                @click="editOpen = true"
                                                class="bg-yellow-500 text-black px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-yellow-400 transition"
                                            >
                                                Editar
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs uppercase tracking-wider hover:bg-red-500 transition"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>

                                            {{-- Modal editar --}}
                                            <div
                                                x-show="editOpen"
                                                x-transition.opacity
                                                x-cloak
                                                @keydown.escape.window="editOpen = false"
                                                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 px-4"
                                            >
                                                <div
                                                    @click.away="editOpen = false"
                                                    class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden"
                                                >
                                                    <div class="bg-black text-white px-6 py-4 flex items-center justify-between">
                                                        <div>
                                                            <h2 class="text-lg font-semibold tracking-wide">Editar usuario</h2>
                                                            <p class="text-xs text-gray-300 mt-1">Actualiza la información del usuario</p>
                                                        </div>

                                                        <button
                                                            type="button"
                                                            @click="editOpen = false"
                                                            class="text-white text-2xl leading-none hover:text-gray-300"
                                                        >
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="p-6">
                                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                                                            @csrf
                                                            @method('PUT')

                                                            <input type="hidden" name="_modal" value="edit">
                                                            <input type="hidden" name="_edit_id" value="{{ $user->id }}">

                                                            <div class="grid md:grid-cols-2 gap-5">
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                        Nombre
                                                                    </label>
                                                                    <input
                                                                        type="text"
                                                                        name="name"
                                                                        value="{{ old('_edit_id') == $user->id ? old('name', $user->name) : $user->name }}"
                                                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                                                        required
                                                                    >
                                                                </div>

                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                        Correo electrónico
                                                                    </label>
                                                                    <input
                                                                        type="email"
                                                                        name="email"
                                                                        value="{{ old('_edit_id') == $user->id ? old('email', $user->email) : $user->email }}"
                                                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                                                        required
                                                                    >
                                                                </div>
                                                            </div>

                                                            <div class="grid md:grid-cols-2 gap-5">
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                        Nueva contraseña
                                                                    </label>
                                                                    <input
                                                                        type="password"
                                                                        name="password"
                                                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                                                    >
                                                                    <p class="text-xs text-gray-500 mt-2">Déjala vacía si no deseas cambiarla.</p>
                                                                </div>

                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                        Confirmar contraseña
                                                                    </label>
                                                                    <input
                                                                        type="password"
                                                                        name="password_confirmation"
                                                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                                                    >
                                                                </div>
                                                            </div>

                                                            @if($errors->any() && old('_edit_id') == $user->id)
                                                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                                                                    @foreach($errors->all() as $error)
                                                                        <p>{{ $error }}</p>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            <div class="flex flex-wrap gap-3 pt-2">
                                                                <button
                                                                    type="submit"
                                                                    class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition"
                                                                >
                                                                    Guardar cambios
                                                                </button>

                                                                <button
                                                                    type="button"
                                                                    @click="editOpen = false"
                                                                    class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-100 transition"
                                                                >
                                                                    Cancelar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            </div>

            {{-- Modal crear --}}
            <div
                x-show="createOpen"
                x-transition.opacity
                x-cloak
                @keydown.escape.window="createOpen = false"
                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 px-4"
            >
                <div
                    @click.away="createOpen = false"
                    class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden"
                >
                    <div class="bg-black text-white px-6 py-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold tracking-wide">Crear usuario</h2>
                            <p class="text-xs text-gray-300 mt-1">Registra un nuevo usuario del sistema</p>
                        </div>

                        <button
                            type="button"
                            @click="createOpen = false"
                            class="text-white text-2xl leading-none hover:text-gray-300"
                        >
                            &times;
                        </button>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="_modal" value="create">

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('_modal') === 'create' ? old('name') : '' }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo electrónico
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('_modal') === 'create' ? old('email') : '' }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Contraseña
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmar contraseña
                                    </label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                                        required
                                    >
                                </div>
                            </div>

                           

                            @if($errors->any() && old('_modal') === 'create')
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-3 pt-2">
                                <button
                                    type="submit"
                                    class="bg-black text-white px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-800 transition"
                                >
                                    Crear usuario
                                </button>

                                <button
                                    type="button"
                                    @click="createOpen = false"
                                    class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-gray-100 transition"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</x-store-layout>