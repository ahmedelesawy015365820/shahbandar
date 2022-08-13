import indexDiscount from "../../view/admin/discount/index";
import createDiscount from "../../view/admin/discount/create";
import editDiscount from "../../view/admin/discount/edit";
import store from "../../store/admin";

export default [
    {
        path: 'discount',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexDiscount',
                component: indexDiscount,
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
                name: 'createDiscount',
                component: createDiscount,
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
                name: 'editDiscount',
                component: editDiscount,
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
