// Configuración específica para Inertia en Railway
export const inertiaConfig = {
    baseURL: import.meta.env.PROD 
        ? 'https://agenda-citas-production.up.railway.app'
        : 'http://localhost:8000',
    
    // Headers para Inertia
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-Inertia': 'true',
        'Accept': 'application/json, text/plain, */*'
    }
};

// Función para obtener URLs absolutas seguras
export const secureUrl = (path: string): string => {
    const base = import.meta.env.PROD 
        ? 'https://agenda-citas-production.up.railway.app'
        : 'http://localhost:8000';
    
    // Asegurar que no haya doble slash
    const cleanBase = base.endsWith('/') ? base.slice(0, -1) : base;
    const cleanPath = path.startsWith('/') ? path : `/${path}`;
    
    return `${cleanBase}${cleanPath}`;
};