import axios from 'axios';

// Obtener token del meta tag
const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
};

// Configurar interceptor para AGREGAR token automáticamente
axios.interceptors.request.use((config) => {
    // Solo para métodos que necesitan CSRF
    if (['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase() || '')) {
        const token = getCsrfToken();
        
        // Agregar header
        config.headers['X-CSRF-TOKEN'] = token;
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        
        // Agregar _token al data si existe
        if (config.data) {
            if (config.data instanceof FormData) {
                if (!config.data.has('_token')) {
                    config.data.append('_token', token);
                }
            } else if (typeof config.data === 'object') {
                config.data = {
                    ...config.data,
                    _token: token
                };
            }
        } else {
            config.data = { _token: token };
        }
    }
    
    return config;
});

// Configuración base
axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.PROD 
    ? window.location.origin 
    : 'http://localhost:8000';

export default axios;