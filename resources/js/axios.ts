import axios from 'axios';

// Configuración para Railway (producción)
if (import.meta.env.PROD) {
    // FORZAR HTTPS en producción
    axios.defaults.baseURL = window.location.origin;
    
    // Configurar headers para Inertia
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-Inertia'] = 'true';
    
    console.log('🔒 Inertia Axios configurado para PRODUCCIÓN:', axios.defaults.baseURL);
} else {
    axios.defaults.baseURL = 'http://localhost:8000';
    console.log('💻 Inertia Axios configurado para DESARROLLO');
}

// Interceptor para manejar redirecciones Inertia
axios.interceptors.response.use(
    response => response,
    error => {
        // Si es un error 409 (Inertia redirection conflict)
        if (error.response?.status === 409 && error.response?.headers['x-inertia-location']) {
            window.location.href = error.response.headers['x-inertia-location'];
            return Promise.reject(error);
        }
        return Promise.reject(error);
    }
);

export default axios;