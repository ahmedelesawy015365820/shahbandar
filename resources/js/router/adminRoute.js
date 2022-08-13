import {createRouter, createWebHistory} from 'vue-router';
import Index from '../view/admin/Dashboard.vue';
import department from './adminRoute/department';
import supplier from './adminRoute/supplier';
import category from './adminRoute/category';
import company from './adminRoute/company';
import job from './adminRoute/job';
import role from './adminRoute/role';
import employee from './adminRoute/employee';
import measure from './adminRoute/measure';
import discount from './adminRoute/discount';
import notification from '../view/admin/notifications';
import Page404 from '../view/admin/Page404.vue';
import middlewarePipeline from "./middlewarePipeline";
import store from "../store/admin";
import guest from "../middleware/admin/guest";
import checkToken from "../middleware/admin/checkToken";
import auth from "../middleware/admin/auth";
import login from "../view/admin/login";
import forgetPassword from "../view/admin/forgetPassword";
import resetPassword from "../view/admin/resetPassword";



const routes = [
    {
        path: '/',
        name: 'loginLang',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: forgetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: resetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/dashboard',
        name: 'partner',
        component: {
            template: '<router-view />',
        },
        meta: {
            middleware: [auth, checkToken]
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Index,
            },
            {
                path: 'notification',
                name: 'notification',
                component: notification,
            },
            ...department,
            ...job,
            ...employee,
            ...role,
            ...supplier,
            ...category,
            ...company,
            ...measure,
            ...discount
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'Page404',
        component: Page404
    },
];

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes
});


router.beforeEach((to, from, next) => {

    if (!to.meta.middleware) return next();
    const middleware = to.meta.middleware;
    const context = {
        to,
        from,
        next,
        store
    };
    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    });
});

export default router;
