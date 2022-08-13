import indexMeasure from "../../view/admin/measure/index";
import createMeasure from "../../view/admin/measure/create";
import editMeasure from "../../view/admin/measure/edit";
import store from "../../store/admin";

export default [
    {
        path: 'measure',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexMeasure',
                component: indexMeasure,
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
                name: 'createMeasure',
                component: createMeasure,
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
                name: 'editMeasure',
                component: editMeasure,
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
