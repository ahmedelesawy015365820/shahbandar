import indexSupplier from "../../view/admin/supplier/index";
import createSupplier from "../../view/admin/supplier/create";
import editSupplier from "../../view/admin/supplier/edit";
import store from "../../store/admin";

export default [
    {
        path: 'supplier',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSupplier',
                component: indexSupplier,
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
                name: 'createSupplier',
                component: createSupplier,
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
                name: 'editSupplier',
                component: editSupplier,
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
