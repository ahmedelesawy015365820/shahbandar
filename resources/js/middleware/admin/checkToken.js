import adminApi from "../../api/adminAxios.js";


export default function checkToken({ next, store }){
    adminApi.get('/v1/auth/checkToken')
         .then((res) => {
             return next();
         })
         .catch((err) => {
             store.commit('authAdmin/logoutToken');
             return next({name: 'login', params: {lang: localStorage.getItem("langAdmin") || 'ar'}});
         })

}

