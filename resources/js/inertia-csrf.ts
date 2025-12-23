import { router } from '@inertiajs/vue3';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    // Interceptor para todas las peticiones Inertia
    router.on('before', (event) => {
        // Solo para métodos que necesitan CSRF
        if (['post', 'put', 'patch', 'delete'].includes(event.detail.visit.method.toLowerCase())) {
            const { data, headers = {} } = event.detail.visit;
            
            // Manejar tanto objetos como FormData
            if (data instanceof FormData) {
                // Para FormData, agregar como campo
                if (!data.has('_token')) {
                    data.append('_token', csrfToken);
                }
            } else if (data && typeof data === 'object') {
                // Para objetos, agregar propiedad
                event.detail.visit.data = {
                    ...data,
                    _token: csrfToken
                };
            } else {
                // Si data es null/undefined, crear objeto
                event.detail.visit.data = { _token: csrfToken };
            }
            
            // Agregar headers
            event.detail.visit.headers = {
                ...headers,
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }
    });
    
    console.log('✅ CSRF configurado para Inertia');
}