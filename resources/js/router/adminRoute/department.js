import indexDepartment from "../../view/admin/department/index";
import createDepartment from "../../view/admin/department/create";
import editDepartment from "../../view/admin/department/edit";
import store from "../../store/admin";

export default [
    {
        path: 'department',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexDepartment',
                component: indexDepartment,
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
                name: 'createDepartment',
                component: createDepartment,
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
                name: 'editDepartment',
                component: editDepartment,
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
