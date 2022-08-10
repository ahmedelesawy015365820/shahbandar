import indexCategory from "../../view/admin/category/index";
import createCategory from "../../view/admin/category/create";
import editCategory from "../../view/admin/category/edit";
import store from "../../store/admin";

export default [
    {
        path: 'category',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCategory',
                component: indexCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createCategory',
                component: createCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editCategory',
                component: editCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
