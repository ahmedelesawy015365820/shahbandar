<template>
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">

        <div class="container">
            <loader v-if="loading" />
            <div class="row align-items-center">
                <div class="col-md-6 offset-md-3">

                    <!-- Forgot Password Content -->
                    <div class="account-content">
                        <div class="align-items-center justify-content-center">
                            <div class="login-right">
                                <div class="login-header text-center">
                                    <img src="/web/img/logo.png" alt="logo" class="img-fluid">
                                    <h3>First, let's find your account</h3>
                                </div>
                                <form @submit.prevent='resetPass'>

                                    <div class="form-focus form-group">
                                        <input v-model="v$.email.$model" class="form-control floating">
                                        <label class="focus-label">Email</label>
                                        <div class="validate" v-if="v$.email.$error">
                                            <span class="text-danger" v-if="v$.email.required.$invalid">email is required.<br /> </span>
                                            <span class="text-danger" v-if="v$.email.email.$invalid">must be a valid email address. </span>
                                        </div>
                                    </div>

                                    <div class="form-group form-focus">
                                        <input type="password" v-model.trim="v$.password.$model" class="form-control floating">
                                        <label class="focus-label">Password</label>
                                        <div class="validate" v-if="v$.password.$error">
                                            <span class="text-danger" v-if="v$.password.required.$invalid">password is required.<br /> </span>
                                            <span class="text-danger" v-if="v$.password.alphaNum.$invalid">must be letters or numbers. <br /></span>
                                            <span class="text-danger" v-if="v$.password.minLength.$invalid">password is must have at least {{ v$.password.minLength.$params.min }} letters. <br /></span>
                                            <span class="text-danger" v-if="v$.password.maxLength.$invalid">password is must have at most {{ v$.password.maxLength.$params.max }} letters. </span>
                                        </div>
                                    </div>

                                    <div class="form-group form-focus">
                                        <input type="password" v-model.trim="v$.password_confirmation.$model" class="form-control floating">
                                        <label class="focus-label">Confirm Password</label>
                                        <div class="validate" v-if="v$.password_confirmation.$error">
                                            <span class="text-danger" v-if="v$.password_confirmation.required.$invalid">confirmtion is required.<br /> </span>
                                            <span class="text-danger" v-if="v$.password_confirmation.sameAs.$invalid">confirmtion other must match. <br /></span>
                                        </div>
                                    </div>

                                    <button class=" btn-color btn btn-primary btn-block btn-lg login-btn text-center" type="submit">
                                        RESET PASSWORD
                                    </button>
                                    <div class="row form-row forget-login">
                                        <div :class="['col-6','text-start','click-forget', 'forget-register']">
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
import {alphaNum, email, maxLength, minLength, required, sameAs} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
    name: "resetPassword",
    setup(){

        let loading = ref(false);
        //start advertiser
        let reset =  reactive({
            data:{
                email:'',
                password:'',
                password_confirmation:'',
                token: ''
            }
        });

        const rules = computed(() => {
            return {
                email: {
                    required,
                    email
                },
                password: {
                    required,
                    minLength: minLength(8),
                    maxLength:maxLength(16),
                    alphaNum
                }
                ,
                password_confirmation: {
                    required,
                    sameAs: sameAs(reset.data.password)
                },
            }
        });

        const v$ = useVuelidate(rules,reset.data);

        return {...toRefs(reset),v$,loading};
    },
    beforeMount() {
        this.data.token = this.$route.query.token;

        if (!this.data.token)
        {
            return this.$router.push({name:'login',params:{lang:localStorage.getItem('langAdmin') || 'ar'}});
        }
        console.log(this.data.token)
    },
    methods: {
        resetPass() {
            this.v$.$validate();
            this.loading = true;

            webApi.post(`/v1/web/reset-password`,this.data)
                .then((res) => {
                    console.log('good');
                    Swal.fire(
                        'تم تغير الرقم السري ',
                        'تم تغير الرقم السري بنجاح تستطيع الان الدخول  .',
                        'نجاح'
                    );

                    this.$router.push({name:'loginPartiner',params: {lang:this.$i18n.locale}})
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

.login-header h3 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 40px;
    color: #161c2d;
}

.validate {
    margin: 4px 0;
}

.form-focus {
    height: 60px;
}
</style>

