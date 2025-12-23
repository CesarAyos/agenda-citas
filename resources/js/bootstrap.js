import axios from 'axios';

// Configuración global para Inertia
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-Inertia'] = 'true';
window.axios.defaults.withCredentials = true;

// Solo en producción forzar HTTPS
if (window.location.protocol === 'https:') {
    window.axios.defaults.baseURL = window.location.origin;
    console.log('🔒 Bootstrap: Configurando Axios para HTTPS');
}