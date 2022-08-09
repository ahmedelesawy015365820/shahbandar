export default function guest({  next,store}){
    if (store.getters["authAdmin/token"]) {

        return next({name: 'dashboard', params: {lang: localStorage.getItem("langAdmin") || 'ar'}});

    } else {
        return next();
    }
}

