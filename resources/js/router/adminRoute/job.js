import indexJob from "../../view/admin/job/index";
import createJob from "../../view/admin/job/create";
import editJob from "../../view/admin/job/edit";
import store from "../../store/admin";

export default [
    {
        path: 'job',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexJob',
                component: indexJob,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('job read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createJob',
                component: createJob,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('job create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editJob',
                component: editJob,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('job edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
