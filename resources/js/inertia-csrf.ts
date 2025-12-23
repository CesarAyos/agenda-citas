import { router } from '@inertiajs/core';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    // Interceptor para todas las peticiones Inertia
    router.on('before', (event) => {
        const visit: any = event.detail.visit;

        // Forzar envío de cookies/credenciales en todas las visitas (ayuda a evitar 419)
        visit.options = {
            ...(visit.options || {}),
            credentials: 'include'
        };

        // Solo para métodos que necesitan CSRF
        if (['post', 'put', 'patch', 'delete'].includes(visit.method.toLowerCase())) {
            const { data, headers = {} } = visit;

            // Manejar tanto objetos como FormData
            if (data instanceof FormData) {
                // Para FormData, agregar como campo
                if (!data.has('_token')) {
                    data.append('_token', csrfToken);
                }
            } else if (data && typeof data === 'object') {
                // Para objetos, agregar propiedad
                visit.data = {
                    ...data,
                    _token: csrfToken
                };
            } else {
                // Si data es null/undefined, crear objeto
                visit.data = { _token: csrfToken };
            }

            // Agregar headers
            visit.headers = {
                ...headers,
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }
    });

    console.log('✅ CSRF configurado para Inertia');
} else {
    console.warn('⚠️ Meta CSRF faltante: añade `meta name="csrf-token"` en la plantilla de producción.');
}