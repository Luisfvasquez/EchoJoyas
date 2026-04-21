<x-store-layout>
    <div x-data="{
        activeSlide: 0,
        slides: [
            { image: '{{ asset('images/rolex.png') }}', brand: 'Colección Exclusiva', title: 'Elegancia Atemporal' },
            { image: '{{ asset('images/omega.png') }}', brand: 'Alta Relojería', title: 'Precisión y Estilo' },
            { image: '{{ asset('images/cartier.png') }}', brand: 'Piezas Únicas', title: 'El Estándar del Lujo' },
            { image: '{{ asset('images/hublot.png') }}', brand: 'Piezas Únicas', title: 'El Estándar del Lujo' },
        ]
    }" x-init="setInterval(() => { activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1 }, 5000)" class="relative w-full h-[70vh] overflow-hidden bg-black">

        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 transform scale-105"
                x-transition:enter-end="opacity-100 transform scale-100"
                class="absolute inset-0 flex flex-col items-center justify-center text-white bg-cover bg-center"
                :style="`background-image: url('${slide.image}');`">

                <div class="absolute inset-0 bg-black bg-opacity-50"></div>

                <div class="relative z-10 text-center px-4">
                    <p class="text-sm md:text-lg tracking-widest uppercase mb-2 text-yellow-400" x-text="slide.brand">
                    </p>
                    <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6 drop-shadow-lg" x-text="slide.title"></h2>
                    <a href="{{ route('shop') }}"
                        class="inline-block px-8 py-3 border-2 border-white text-white font-semibold uppercase tracking-wider hover:bg-white hover:text-black transition duration-300">
                        Descubrir
                    </a>
                </div>
            </div>
        </template>

        <button @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1"
            class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-5xl transition">‹</button>
        <button @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-5xl transition">›</button>

        <div class="absolute bottom-6 left-0 right-0 flex justify-center space-x-3 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index"
                    :class="{ 'bg-yellow-400 w-8': activeSlide === index, 'bg-white/50 w-3': activeSlide !== index }"
                    class="h-3 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>

    <div class="container mx-auto px-4 py-20 text-center">
        <h2 class="text-2xl sm:text-4xl font-serif mb-6 text-gray-900">Relojes y Oro Ecko Joyas</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-8 leading-relaxed">
            Joyeria de lujo en Caracas, Venezuela. Especialistas en relojes de alta gama y piezas de oro. <br>
            C.C.C.T,Centro Joyero,Local 55. Caracas, Venezuela. <br>
        </p>

        <div class="w-full mb-12">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13196.01593316803!2d-66.86504189158399!3d10.484972444061285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2a58562d6be2f7%3A0x68c1b72d71c1d7d6!2sCentro%20Ciudad%20Comercial%20Tamanaco%20CCCT!5e0!3m2!1ses-419!2sve!4v1776699225659!5m2!1ses-419!2sve"
                class="w-full h-[400px] md:h-[450px] border-0 rounded-lg shadow-md" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto mt-8">
            <div class="p-8 border-t-4 border-black  bg-white shadow-sm hover:shadow-lg transition duration-300">
                <svg class="w-12 h-12 mx-auto mb-4 text-black" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 640">
                    <path
                        d="M272 112C272 85.5 293.5 64 320 64C346.5 64 368 85.5 368 112C368 138.5 346.5 160 320 160C293.5 160 272 138.5 272 112zM224 256C224 238.3 238.3 224 256 224L320 224C337.7 224 352 238.3 352 256L352 512L384 512C401.7 512 416 526.3 416 544C416 561.7 401.7 576 384 576L256 576C238.3 576 224 561.7 224 544C224 526.3 238.3 512 256 512L288 512L288 288L256 288C238.3 288 224 273.7 224 256z" />
                </svg>
                <h4 class="text-xl font-bold mb-4 uppercase tracking-wide">Qué Vendemos</h4>
                <p class="text-gray-600 leading-relaxed">Relojería de alta gama, piezas vintage de colección e
                    instrumentos de precisión.</p>
            </div>
            <div class="p-8 border-t-4 border-black bg-white shadow-sm hover:shadow-lg transition duration-300">
                <svg class="w-12 h-12 mx-auto mb-4 text-black-500" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path
                        d="M208 64C172.7 64 144 92.7 144 128L144 512C144 547.3 172.7 576 208 576L432 576C467.3 576 496 547.3 496 512L496 128C496 92.7 467.3 64 432 64L208 64zM280 480L360 480C373.3 480 384 490.7 384 504C384 517.3 373.3 528 360 528L280 528C266.7 528 256 517.3 256 504C256 490.7 266.7 480 280 480z" />
                </svg>
                <h4 class="text-xl font-bold mb-4 uppercase tracking-wide">Contactanos</h4>
                <p class="text-gray-600 leading-relaxed">Asesoría personalizada por expertos. Evaluamos sus piezas y le
                    guiamos en su compra. <br>
                    <a href="https://wa.me/584143284935" target="_blank"
                        class="inline-flex items-center justify-center px-6 py-2 text-white bg-green-500 rounded-full hover:bg-green-600 transition-colors font-semibold shadow-md">
                        WhatsApp
                    </a>
                </p>

            </div>
            <div class="p-8 border-t-4 border-black bg-white shadow-sm hover:shadow-lg transition duration-300">
                <svg class="w-12 h-12 mx-auto mb-4 text-black" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 640 640">
                    <path
                        d="M305 151.1L320 171.8L335 151.1C360 116.5 400.2 96 442.9 96C516.4 96 576 155.6 576 229.1L576 231.7C576 343.9 436.1 474.2 363.1 529.9C350.7 539.3 335.5 544 320 544C304.5 544 289.2 539.4 276.9 529.9C203.9 474.2 64 343.9 64 231.7L64 229.1C64 155.6 123.6 96 197.1 96C239.8 96 280 116.5 305 151.1z" />
                </svg>
                <h4 class="text-xl font-bold mb-4 uppercase tracking-wide">Disfruta</h4>
                <p class="text-gray-600 leading-relaxed">Más que medir el tiempo, invierte en una obra de arte mecánica
                    que trasciende generaciones.</p>
            </div>
        </div>
        <div class="bg-gray-50 py-20 border-t border-gray-200">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h3 class="text-4xl font-serif text-gray-900 mb-4">Piezas Destacadas</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Descubre una selección de nuestros guardatiempos más exclusivos, elegidos por su artesanía
                        excepcional, precisión mecánica y legado histórico.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">

                    <div
                        class="bg-white border rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300 group">
                        <div class="overflow-hidden h-72">
                            <img src="{{ asset('images/reloj1.jpg') }}" alt="Bvlgari Diagono"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-8">
                            <p class="text-xs text-yellow-600 font-bold tracking-widest uppercase mb-2">Bvlgari</p>
                            <h4 class="text-2xl font-serif font-bold mb-2">Ulysse Nardin</h4>
                            <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                Un cronógrafo deportivo con diseño audaz. La combinación perfecta entre la elegancia
                                italiana y la precisión suiza, ideal para el hombre moderno.
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('shop') }}"
                                    class="text-sm font-bold uppercase tracking-wider text-black border-b-2 border-yellow-400 pb-1 hover:text-yellow-600 transition">Ver
                                    Ver catalogo</a>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white border rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300 group">
                        <div class="overflow-hidden h-72">
                            <img src="{{ asset('images/reloj2.jpg') }}" alt="Seiko Monster"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-8">
                            <p class="text-xs text-yellow-600 font-bold tracking-widest uppercase mb-2">Seiko</p>
                            <h4 class="text-2xl font-serif font-bold mb-2">Oyster Perpetual Candy Pink</h4>
                            <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                Reconocido mundialmente por su resistencia extrema y su icónico dial naranja. Una
                                herramienta indispensable y robusta para el buceo profesional.
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('shop') }}"
                                    class="text-sm font-bold uppercase tracking-wider text-black border-b-2 border-yellow-400 pb-1 hover:text-yellow-600 transition">Ver
                                    Ver catalogo</a>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white border rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300 group">
                        <div class="overflow-hidden h-72">
                            <img src="{{ asset('images/reloj3.jpg') }}" alt="Tissot Classic"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-8">
                            <p class="text-xs text-yellow-600 font-bold tracking-widest uppercase mb-2">Tissot</p>
                            <h4 class="text-2xl font-serif font-bold mb-2">Cartier Santos</h4>
                            <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                                El pionero de la aviación y la elegancia moderna.
                                Reconocido históricamente como el primer reloj de pulsera moderno para caballero, el
                                Cartier Santos nació en 1904 de la necesidad del aviador Alberto Santos-Dumont de leer
                                la hora en pleno vuelo. Su inconfundible diseño geométrico, caracterizado por una caja
                                cuadrada y tornillos expuestos en el bisel, desafió las normas estéticas de su época.
                                Hoy, se mantiene como un ícono de sofisticación atemporal, siendo una pieza que fusiona
                                a la perfección la funcionalidad técnica con un refinamiento absoluto.
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('shop') }}"
                                    class="text-sm font-bold uppercase tracking-wider text-black border-b-2 border-yellow-400 pb-1 hover:text-yellow-600 transition">Ver
                                    Ver catalogo</a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('shop') }}"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-black text-white font-semibold uppercase tracking-wider hover:bg-yellow-500 hover:text-black transition duration-300 rounded-full">
                        <span>Ver Todo El Catálogo</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <a href="https://wa.me/584143284935" target="_blank"
        class="fixed bottom-8 right-8 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-transform duration-300 z-50 flex items-center justify-center cursor-pointer group">

        <span
            class="absolute right-16 bg-white text-black text-xs font-bold px-3 py-2 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
            ¡Escríbenos!
        </span>

        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path
                d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L3.2 472.9l113.1-29.6C150.8 463 187.1 472 223.8 472c122.4 0 222-99.6 222-222 0-59.3-23.1-115.1-64.9-157.1zM223.8 434.5c-33.6 0-66.6-9-95.2-26.1l-6.8-4-70.6 18.5 18.8-68.9-4.4-7c-18.7-29.8-28.6-64-28.6-99.5 0-103 83.8-186.9 186.9-186.9 50 0 96.9 19.5 132.3 54.8 35.4 35.3 54.9 82.2 54.9 132.1.1 103.1-83.7 186.9-186.8 186.9zm108.6-149c-6-3-35.3-17.4-40.8-19.4-5.5-2-9.6-3-13.6 3s-15.6 19.4-19.1 23.4-7 4.5-13 1.5c-6-3-25.2-9.3-48-29.6-17.7-15.8-29.7-35.3-33.2-41.2s-.4-9 2.6-12c2.7-2.7 6-7 9-10.5 1.7-2 3-3.3 4.5-5.3 1.5-2 2.3-3.8 3.3-6.5s.5-5.3-.5-7.7c-1-2.4-9.6-23.1-13.1-31.6-3.4-8.3-7.1-7.2-9.6-7.3-2.5-.1-5.3-.1-8.1-.1s-7.4 1.1-11.3 5.3c-3.8 4.2-14.6 14.3-14.6 34.9S130.3 228 131.8 230c1.5 1.9 20.1 31.9 50.1 44.9 7.1 3.1 12.7 5 17.1 6.4 7.2 2.3 13.7 1.9 18.8 1.2 5.7-.8 17.4-7.1 19.9-14 2.5-6.9 2.5-12.8 1.8-14s-2.6-2-8.6-5z" />
        </svg>
    </a>

</x-store-layout>
