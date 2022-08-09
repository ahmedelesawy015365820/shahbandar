import { createStore } from 'vuex';
import authAdmin from "./admin/authAdmin";

const store = createStore({
    state () {
        return {
        }
    },
    getters:{
    },
    mutations: {
    },
    actions:{

    },
    modules: {
        authAdmin
    }
});

export default store;
