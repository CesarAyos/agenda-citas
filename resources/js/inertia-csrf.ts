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

        // Leer cookie XSRF-TOKEN (Laravel) y decodificarla
        const xsrfCookie = document.cookie
            .split(';')
            .map(c => c.trim())
            .find(c => c.startsWith('XSRF-TOKEN='));
        const xsrfToken = xsrfCookie ? decodeURIComponent(xsrfCookie.split('=')[1]) : null;

        // Asegurar encabezados comunes
        const { headers = {} } = visit;
        visit.headers = {
            ...headers,
            'X-Requested-With': 'XMLHttpRequest'
        };

        // Si tenemos token en meta, añadirlo siempre en encabezados (mejora compatibilidad)
        if (csrfToken) {
            visit.headers['X-CSRF-TOKEN'] = csrfToken;
        }

        // Si tenemos cookie XSRF, añadir encabezado específico que Laravel reconoce
        if (xsrfToken) {
            visit.headers['X-XSRF-TOKEN'] = xsrfToken;
        }

        // Para métodos que necesitan CSRF, además asegurar que _token esté en los datos
        if (['post', 'put', 'patch', 'delete'].includes(visit.method.toLowerCase())) {
            const { data } = visit;

            // Manejar tanto objetos como FormData
            if (data instanceof FormData) {
                if (!data.has('_token') && csrfToken) {
                    data.append('_token', csrfToken);
                }
            } else if (data && typeof data === 'object') {
                visit.data = {
                    ...data,
                    _token: csrfToken
                };
            } else {
                visit.data = { _token: csrfToken };
            }
        }
    });

    console.log('✅ CSRF configurado para Inertia');
} else {
    console.warn('⚠️ Meta CSRF faltante: añade `meta name="csrf-token"` en la plantilla de producción.');
}