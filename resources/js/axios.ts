import axios from 'axios';

// Obtener el token CSRF de la meta tag de Laravel
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Configurar Axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken || '';
axios.defaults.withCredentials = true; // IMPORTANTE para cookies

// Configurar base URL
if (import.meta.env.PROD) {
    axios.defaults.baseURL = window.location.origin;
    console.log('🔒 Axios configurado para PRODUCCIÓN con CSRF');
} else {
    axios.defaults.baseURL = 'http://localhost:8000';
}

export default axios;