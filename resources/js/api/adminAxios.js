import axios from "axios";
import Cookies from "js-cookie";

const adminApi = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/'
});

adminApi.interceptors.request.use(
    function (config) {
        config.headers['lang'] = localStorage.getItem("langAdmin") || 'ar';
        config.headers['Authorization'] = "Bearer "+ (Cookies.get("tokenAdmin") || '');
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

adminApi.defaults.headers.common['secretApi'] = 'Snr92EUKCmrE06PiJ';
// end axios

export default adminApi;
