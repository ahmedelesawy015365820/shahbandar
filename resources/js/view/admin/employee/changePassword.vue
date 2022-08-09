<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Employees')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexEmployee', params: {lang: locale || 'ar'}}">{{$t('global.Employees')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.ChanePassword')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading" />
                        <div class="card-body">
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexEmployee', params: {lang: locale || 'ar'}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['en.password']">{{ errors['password'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['ar.password_confirmation']">{{ errors['password_confirmation'][0] }}<br /> </div>
                                    <form @submit.prevent="editEmployee" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-12 mb-12">
                                                <label for="validationCustom10">{{ $t('global.Password') }}</label>
                                                <input type="password" v-model.trim="v$.password.$model"
                                                       id="validationCustom09"
                                                       :class="['form-control',{'is-invalid':v$.password.$error,'is-valid':!v$.password.$invalid}]"
                                                       :placeholder="$t('global.Password')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                                <div class="invalid-feedback">
                                                        <span
                                                            v-if="v$.password.required.$invalid">{{$t('global.PasswordIsRequired')}}<br/> </span>
                                                    <span v-if="v$.password.alphaNum.$invalid">{{$t('global.MustBeLettersOrNumbers')}} <br/></span>
                                                    <span v-if="v$.password.minLength.$invalid">{{$t('global.PasswordIsMustHaveAtLeast')}} {{
                                                            v$.password.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span v-if="v$.password.maxLength.$invalid">{{$t('global.PasswordIsMustHaveAtMost')}} {{
                                                            v$.password.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-12">
                                                <label for="validationCustom09">{{ $t('global.confirm') }}</label>
                                                <input type="password" v-model.trim="v$.password_confirmation.$model"
                                                       id="validationCustom10"
                                                       :class="['form-control',{'is-invalid':v$.password_confirmation.$error,'is-valid':!v$.password_confirmation.$invalid}]"
                                                       :placeholder="$t('global.confirm')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                                <div class="invalid-feedback">
                                                    <span v-if="v$.password_confirmation.required.$invalid">{{$t('global.ConfirmIsRequired')}}<br/> </span>
                                                    <span v-if="v$.password_confirmation.sameAs.$invalid">{{$t('global.ConfirmationMustMatchPassword')}} <br/></span>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
    </div>
</template>

<script>
import {computed, onMounted, reactive,toRefs,inject,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, alphaNum, sameAs} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editIncome",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject('emitter');

        const {id} = toRefs(props)
        const {t} = useI18n({});

        let loading = ref(false);



        //start design
        let addEmployee =  reactive({
            data:{
                password: '',
                password_confirmation: '',
            }
        });

        const rules = computed(() => {
            return {
                password: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(16),
                    alphaNum
                }
                ,
                password_confirmation: {
                    required,
                    sameAs: sameAs(addEmployee.data.password)
                },
            }
        });


        const v$ = useVuelidate(rules,addEmployee.data);


        return {t,loading,...toRefs(addEmployee),v$};
    },
    methods: {
        editEmployee(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/employee/changePassword/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        }
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
</style>
