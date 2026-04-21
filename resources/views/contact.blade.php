<x-store-layout>
    <div class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4 tracking-wider">Contacto</h1>
            <p class="text-gray-400 max-w-xl mx-auto text-sm md:text-base">
                Estamos a su entera disposición para asesorarle en su próxima adquisición, valuar sus piezas o responder a cualquier inquietud.
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16 max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20">
            
            <div class="bg-white p-8 md:p-10 shadow-lg rounded-xl border border-gray-100">
                <h3 class="text-2xl font-serif mb-6 text-gray-900">Envíenos un mensaje</h3>
                
                <form id="whatsappForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                            <input type="text" id="name" required 
                                class="w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico *</label>
                            <input type="email" id="email" required 
                                class="w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
                        <select id="subject" class="w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3">
                            <option value="Interés en comprar un reloj/joya">Interés en comprar un reloj/joya</option>
                            <option value="Deseo vender o tasar una pieza">Deseo vender o tasar una pieza</option>
                            <option value="Servicio técnico o reparación">Servicio técnico o reparación</option>
                            <option value="Otra consulta">Otra consulta</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje *</label>
                        <textarea id="message" rows="4" required placeholder="Escriba aquí los detalles del reloj que busca o la consulta que tiene..."
                            class="w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm py-2 px-3"></textarea>
                    </div>

                    <button type="button" onclick="sendToWhatsApp()" class="w-full bg-[#25D366] text-white font-bold uppercase tracking-widest py-3 px-4 rounded-full hover:bg-green-600 transition duration-300 flex items-center justify-center gap-2 shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L3.2 472.9l113.1-29.6C150.8 463 187.1 472 223.8 472c122.4 0 222-99.6 222-222 0-59.3-23.1-115.1-64.9-157.1zM223.8 434.5c-33.6 0-66.6-9-95.2-26.1l-6.8-4-70.6 18.5 18.8-68.9-4.4-7c-18.7-29.8-28.6-64-28.6-99.5 0-103 83.8-186.9 186.9-186.9 50 0 96.9 19.5 132.3 54.8 35.4 35.3 54.9 82.2 54.9 132.1.1 103.1-83.7 186.9-186.8 186.9zm108.6-149c-6-3-35.3-17.4-40.8-19.4-5.5-2-9.6-3-13.6 3s-15.6 19.4-19.1 23.4-7 4.5-13 1.5c-6-3-25.2-9.3-48-29.6-17.7-15.8-29.7-35.3-33.2-41.2s-.4-9 2.6-12c2.7-2.7 6-7 9-10.5 1.7-2 3-3.3 4.5-5.3 1.5-2 2.3-3.8 3.3-6.5s.5-5.3-.5-7.7c-1-2.4-9.6-23.1-13.1-31.6-3.4-8.3-7.1-7.2-9.6-7.3-2.5-.1-5.3-.1-8.1-.1s-7.4 1.1-11.3 5.3c-3.8 4.2-14.6 14.3-14.6 34.9S130.3 228 131.8 230c1.5 1.9 20.1 31.9 50.1 44.9 7.1 3.1 12.7 5 17.1 6.4 7.2 2.3 13.7 1.9 18.8 1.2 5.7-.8 17.4-7.1 19.9-14 2.5-6.9 2.5-12.8 1.8-14s-2.6-2-8.6-5z"/></svg>
                        Enviar Consulta a WhatsApp
                    </button>
                </form>
            </div>

            <div class="flex flex-col justify-between">
                
                <div class="space-y-8 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Llámenos</h4>
                            <p class="text-gray-600">(0212) 959.1206</p>
                            <p class="text-gray-600">(0414) 328.4935 <span class="text-xs text-green-600 font-bold">(WhatsApp)</span></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Visítenos</h4>
                            <p class="text-gray-600">C.C.C.T, Centro Joyero, Local 55.</p>
                            <p class="text-gray-600">Caracas, Venezuela.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Horario de Atención</h4>
                            <p class="text-gray-600">Lunes a Sábado: 10:00 AM - 6:00 PM</p>
                            <p class="text-gray-600">Domingos: Cerrado</p>
                        </div>
                    </div>
                </div>

                <div class="w-full h-64 bg-gray-200 rounded-xl overflow-hidden shadow-inner">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13196.01593316803!2d-66.86504189158399!3d10.484972444061285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2a58562d6be2f7%3A0x68c1b72d71c1d7d6!2sCentro%20Ciudad%20Comercial%20Tamanaco%20CCCT!5e0!3m2!1ses-419!2sve!4v1776699225659!5m2!1ses-419!2sve" 
                        class="w-full h-full border-0" allowfullscreen="" loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>
    </div>

    <script>
        function sendToWhatsApp() {
            // Capturar los valores de los inputs
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;

            // Validación simple para asegurar que llenaron todo
            if(name === '' || email === '' || message === '') {
                alert('Por favor, complete todos los campos requeridos antes de enviar.');
                return;
            }

            // Construir el mensaje formateado para WhatsApp
            const numeroDestino = "584143284935"; 
            const textoWhatsApp = `¡Hola! Vengo desde la página web.%0A%0A*Mi nombre es:* ${name}%0A*Mi correo:* ${email}%0A*Motivo de contacto:* ${subject}%0A%0A*Mensaje:*%0A${message}`;

            // Crear el enlace (Funciona tanto en PC como en móviles)
            const url = `https://wa.me/${numeroDestino}?text=${textoWhatsApp}`;

            // Abrir WhatsApp en una nueva pestaña
            window.open(url, '_blank');
        }
    </script>
</x-store-layout>