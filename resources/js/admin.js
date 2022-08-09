require('./bootstrap');


import { createApp } from 'vue';
import store from './store/admin';
import router  from './router/adminRoute';
import Admin from "./Admin.vue";
import mitt from 'mitt';
import Notifications from '@kyvg/vue3-notification';

const emitter = mitt();

const admin = createApp(Admin);

admin.provide('emitter', emitter);

import loader from './components/loader';
import LaravelVuePagination from 'laravel-vue-pagination';
import breadcrumb from "./view/admin/projectsManagement/utilize/breadcrumb";

admin.component('loader', loader);
admin.component('Pagination', LaravelVuePagination);
admin.component('Breadcrumb', breadcrumb);

admin.use(store).use(router).use(Notifications).mount('#admin');


import "./assets/admin/custom-admin.css";


// set lang
if(!localStorage.getItem("langAdmin")){
    localStorage.setItem('langAdmin','ar');
}

let tagHtml = document.querySelector('html'),
    styleLink = document.getElementById('style_link');


if(localStorage.getItem("langAdmin") == 'ar'){
    tagHtml.setAttribute('dir', 'rtl');
    styleLink.setAttribute('href', 'https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css');
}else{
    tagHtml.setAttribute('dir', 'ltr');
    styleLink.setAttribute('href','');
}

