import indexEmployee from "../../view/admin/employee/index";
import createEmployee from "../../view/admin/employee/create";
import editEmployee from "../../view/admin/employee/edit";
import changePasswordEmployee from "../../view/admin/employee/changePassword";
import store from "../../store/admin";

export default [
    {
        path: 'employee',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexEmployee',
                component: indexEmployee,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('employee read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createEmployee',
                component: createEmployee,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('employee create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editEmployee',
                component: editEmployee,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('employee edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'changePassword/:id(\\d+)',
                name: 'changePasswordEmployee',
                component: changePasswordEmployee,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('employeeChangePassword edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
