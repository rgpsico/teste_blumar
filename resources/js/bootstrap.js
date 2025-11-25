import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Para APIs com Bearer token (Sanctum), não precisamos do CSRF token
// O token de autorização é suficiente
