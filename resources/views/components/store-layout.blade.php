<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecko Joyas</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    x-data="{
        loginOpen: {{ $errors->any() ? 'true' : 'false' }},
        mobileMenuOpen: false
    }"
    class="font-sans antialiased text-gray-900 bg-white"
>
    <nav class="bg-black text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4">

                <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo Ecko Joyas" class="h-10 w-auto rounded-full shrink-0">
                    <span class="text-lg sm:text-2xl font-serif tracking-widest truncate">Ecko Joyas</span>
                </a>

                {{-- Menú escritorio --}}
                <div class="hidden md:flex uppercase text-xs font-semibold">
                    <a href="{{ route('home') }}"
                        class="border border-white px-4 py-2 hover:bg-white hover:text-black transition duration-300">Inicio</a>
                    <a href="{{ route('shop') }}"
                        class="border border-white px-4 py-2 hover:bg-white hover:text-black transition duration-300">Tienda</a>
                    <a href="{{ route('how-to-buy') }}"
                        class="border border-white px-4 py-2 hover:bg-white hover:text-black transition duration-300">Cómo Comprar</a>
                    <a href="{{ route('contact') }}"
                        class="border border-white px-4 py-2 hover:bg-white hover:text-black transition duration-300">Contacto</a>
                    <a href="{{ route('blog') }}"
                        class="border border-white px-4 py-2 hover:bg-white hover:text-black transition duration-300">Blog</a>
                </div>

                {{-- Acciones escritorio --}}
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.categories.index') }}"
                                class="text-yellow-400 text-sm font-bold hover:text-yellow-300 border border-yellow-400 px-4 py-2 transition">
                                Panel Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="text-xs text-gray-300 hover:text-white transition border border-white px-4 py-2">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            @click="loginOpen = true"
                            class="text-xs text-gray-400 hover:text-white transition border border-white px-4 py-2">
                            Iniciar Sesión
                        </button>
                    @endauth
                </div>

                {{-- Botón móvil --}}
                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex items-center justify-center border border-white p-2 rounded-lg hover:bg-white hover:text-black transition"
                    aria-label="Abrir menú"
                >
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Menú móvil --}}
            <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden mt-4 border-t border-white/20 pt-4">
                <div class="flex flex-col gap-2 uppercase text-xs font-semibold">
                    <a href="{{ route('home') }}"
                        @click="mobileMenuOpen = false"
                        class="border border-white px-4 py-3 hover:bg-white hover:text-black transition duration-300 rounded-lg">
                        Inicio
                    </a>

                    <a href="{{ route('shop') }}"
                        @click="mobileMenuOpen = false"
                        class="border border-white px-4 py-3 hover:bg-white hover:text-black transition duration-300 rounded-lg">
                        Tienda
                    </a>

                    <a href="{{ route('how-to-buy') }}"
                        @click="mobileMenuOpen = false"
                        class="border border-white px-4 py-3 hover:bg-white hover:text-black transition duration-300 rounded-lg">
                        Cómo Comprar
                    </a>

                    <a href="{{ route('contact') }}"
                        @click="mobileMenuOpen = false"
                        class="border border-white px-4 py-3 hover:bg-white hover:text-black transition duration-300 rounded-lg">
                        Contacto
                    </a>

                    <a href="{{ route('blog') }}"
                        @click="mobileMenuOpen = false"
                        class="border border-white px-4 py-3 hover:bg-white hover:text-black transition duration-300 rounded-lg">
                        Blog
                    </a>
                </div>

                <div class="mt-4 flex flex-col gap-2">
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.categories.index') }}"
                                @click="mobileMenuOpen = false"
                                class="text-center text-yellow-400 font-bold border border-yellow-400 px-4 py-3 rounded-lg">
                                Panel Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-center text-gray-300 hover:text-white transition border border-white px-4 py-3 rounded-lg">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            @click="loginOpen = true; mobileMenuOpen = false"
                            class="text-gray-300 hover:text-white transition border border-white px-4 py-3 rounded-lg">
                            Iniciar Sesión
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @auth
        @if (auth()->user()->is_admin)
            <div class="bg-yellow-500 text-black py-2 shadow-sm">
                <div class="container mx-auto px-4 flex flex-wrap items-center gap-2 text-[11px] sm:text-xs md:text-sm font-semibold uppercase tracking-wide">
                    <span class="mr-2">Modo administrador</span>

                    <a href="{{ route('admin.products.index') }}" class="px-3 py-1 bg-white rounded-full">
                        Productos
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="px-3 py-1 bg-white rounded-full">
                        Categorías
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-1 bg-white rounded-full">
                        Usuarios
                    </a>
                </div>
            </div>
        @endif
    @endauth

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm tracking-wider uppercase mb-2">Relojes y Oro Ecko Joyas &copy; {{ date('Y') }}</p>
            <p class="text-xs text-gray-400">Elegancia y precisión en cada segundo.</p>
        </div>
    </footer>

    @guest
        <div
            x-show="loginOpen"
            x-transition.opacity
            x-cloak
            @keydown.escape.window="loginOpen = false"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 px-4"
        >
            <div
                @click.away="loginOpen = false"
                x-transition
                class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
            >
                <div class="bg-black text-white px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold tracking-wide">Acceso administrativo</h2>
                        <p class="text-xs text-gray-300 mt-1">Solo para administración de Ecko Joyas</p>
                    </div>
                    <button @click="loginOpen = false" class="text-white text-xl leading-none hover:text-gray-300">
                        &times;
                    </button>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black"
                            >
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black"
                            >
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-black focus:ring-black"
                                >
                                Recordarme
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-black">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-black text-white py-3 rounded-lg uppercase text-sm tracking-wider hover:bg-gray-800 transition">
                            Entrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endguest
</body>

</html>