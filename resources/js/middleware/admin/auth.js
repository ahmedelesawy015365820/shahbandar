export default function auth({ next, store }){
    if (!store.getters["authAdmin/token"]) {
        return next({name: 'login', params: {lang: localStorage.getItem("langAdmin") || 'ar'}});
    } else {
        return next();
    }
}
