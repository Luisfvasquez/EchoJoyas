const CACHE_NAME = 'echo-joyas-v1';

// Escuchar el evento de instalación
self.addEventListener('install', event => {
    console.log('Service Worker instalando...');
    // Opcional: Aquí podrías cachear tu página de inicio ('/')
});

// Interceptar las peticiones de red (Obligatorio para que PWA sea instalable en algunos navegadores)
self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});