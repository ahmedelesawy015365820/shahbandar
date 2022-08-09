import indexRole from "../../view/admin/role/index";
import createRole from "../../view/admin/role/create";
import editRole from "../../view/admin/role/edit";
import store from "../../store/admin";

export default [
    {
        path: 'role',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexRole',
                component: indexRole,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('role read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createRole',
                component: createRole,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('role create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editRole',
                component: editRole,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('role edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
