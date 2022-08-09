<template>
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">

        <loader v-if="loading" />
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-6 offset-md-3">

                    <!-- Forgot Password Content -->
                    <div class="account-content">
                        <div class="align-items-center justify-content-center">
                            <div class="login-right">
                                <div class="login-header text-center">
                                    <img src="/web/img/logo.png" alt="logo" class="img-fluid">
                                    <p>Please enter your email</p>
                                </div>
                                <form @submit.prevent="forgetPassword">
                                    <div class="form-focus">
                                        <input v-model="v$.email.$model" class="form-control floating">
                                        <label class="focus-label">Email</label>
                                    </div>
                                    <div class="validate" v-if="v$.email.$error">
                                        <span class="text-danger" v-if="v$.email.required.$invalid">email is required.<br /> </span>
                                        <span class="text-danger" v-if="v$.email.email.$invalid">must be a valid email address. </span>
                                    </div>

                                    <button class=" btn-color btn btn-primary btn-block btn-lg login-btn text-center" type="submit">
                                        FIND OUT PASSWORD
                                    </button>
                                    <div class="row form-row forget-login">
                                        <div :class="['col-6','text-start','click-forget','forget-register']">
                                            <router-link  :to="{name:'login'}">Remember your password?</router-link>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Forgot Password Content -->

                </div>
            </div>

        </div>
    </div>
    <!-- /Main Wrapper -->
</template>

<script>
import {computed, reactive, ref, toRefs} from "vue";
import {email, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
    name: "forgetPassword",
    setup(){
        let loading = ref(false);

        //start advertiser
        let forget =  reactive({
            data:{
                email:'',
            }
        });

        const rules = computed(() => {
            return {
                email: {
                    required,
                    email
                }
            }
        });

        const v$ = useVuelidate(rules,forget.data);

        return {...toRefs(forget),v$,loading};
    },
    methods:{
        forgetPassword(){
            this.v$.$validate();
            if(!this.v$.$error){
                this.loading = true;

                webApi.post(`/v1/web/forgot-password`,this.data)
                    .then((res) => {
                        Swal.fire(
                            'تم ارسال رساله',
                            'تم ارسال رساله الي البريد الالكتروني  .',
                            'نجاح'
                        );

                        this.$router.push({name:'login',params: {lang:this.$i18n.locale}})
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'يوجد خطا في الاميل ...',
                            text: 'يرجاء اعاده كتابه الاميل بطريقه صحيحه مره اخري !',
                        });
                        console.log(err.response);
                    })
                    .finally(() => {
                        loading.value = false;
                    });

            }
        }
    }
}
</script>

<style scoped>
.container,.row{
    height: 100%;
}
.login-right .login-header img{
    height: 100px;
    width: 210px;
}

.login-header img{
    height: 50px;
    width: 55px;
}

.login-right .dont-have a, .forget-register a:hover ,.login-register a {
    color: #fcb00c;
}

.btn-color:hover{
    background-color: #fcb00c;
    border-color: #fcb00c ;
}

.login-right .dont-have a, .click-forget a:hover ,.click a{
    color: #fcb00c;
}

.click-forget{
    margin-top: 20px;
}

a {
    color: #000;
    text-decoration: none;
}

.btn-primary {
    margin-top: 20px;
}
</style>
