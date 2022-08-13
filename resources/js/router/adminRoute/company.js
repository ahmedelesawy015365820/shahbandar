import indexCompany from "../../view/admin/company/index";
import createCompany from "../../view/admin/company/create";
import editCompany from "../../view/admin/company/edit";
import store from "../../store/admin";

export default [
    {
        path: 'company',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCompany',
                component: indexCompany,
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
                name: 'createCompany',
                component: createCompany,
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
                name: 'editCompany',
                component: editCompany,
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
