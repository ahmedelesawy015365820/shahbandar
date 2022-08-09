<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Department')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexDepartment'}">global.Department</router-link></li>
                            <li class="breadcrumb-item active">department.EditDepartment</li>
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
                                    :to="{name: 'indexDepartment'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    global.back
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['en.name']">{{ errors['en.name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['ar.name']">{{ errors['ar.name'][0] }}<br /> </div>
                                    <form @submit.prevent="editDepartment" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">global.NameEn</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.en.name.$model"
                                                       id="validationCustom01"
                                                       placeholder="$t('global.NameEn')"
                                                       :class="{'is-invalid':v$.en.name.$error,'is-valid':!v$.en.name.$invalid}"
                                                >
                                                <div class="valid-feedback">global.LooksGood</div>
                                                <div class="invalid-feedback">
<!--                                                    <span v-if="v$.en.name.required.$invalid">{{$t('global.NameEnIsRequired')}}<br /> </span>-->
<!--                                                    <span v-if="v$.en.name.minLength.$invalid">{{$t('global.NameEnIsMustHaveAtLeast')}} {{ v$.en.name.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>-->
<!--                                                    <span v-if="v$.en.name.maxLength.$invalid">{{$t('global.NameEnIsMustHaveAtMost')}} {{ v$.en.name.maxLength.$params.max }} {{$t('global.Letters')}} </span>-->
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{$t('global.NameAr')}}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.ar.name.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.ar.name.$error,'is-valid':!v$.ar.name.$invalid}"
                                                       placeholder="global.NameAr"
                                                >
                                                <div class="valid-feedback">global.LooksGood</div>
                                                <div class="invalid-feedback">
<!--                                                    <span v-if="v$.ar.name.required.$invalid">{{$t('global.NameArIsRequired')}} <br /></span>-->
<!--                                                    <span v-if="v$.ar.name.minLength.$invalid">{{$t('global.NameArIsMustHaveAtLeast')}} {{ v$.ar.name.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>-->
<!--                                                    <span v-if="v$.ar.name.maxLength.$invalid">{{$t('global.NameArIsMustHaveAtMost')}} {{ v$.ar.name.maxLength.$params.max }} {{$t('global.Letters')}} </span>-->
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">global.Submit</button>
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
import {required,minLength,maxLength} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editDepartment",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject('emitter');

        const {id} = toRefs(props)
        // get create Package
        let department = ref({});
        let loading = ref(false);


        let getMainDepartmentViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/department/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addDepartment.data.en.name = l.department.translations[1].name;
                    addDepartment.data.ar.name = l.department.translations[0].name;
                    console.log(l)
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainDepartmentViews();
        });


        //start design
        let addDepartment =  reactive({
            data:{
                en:{ name : ''},
                ar:{ name : ''},
            }
        });

        const rules = computed(() => {
            return {
                en:{
                    name: {
                        minLength: minLength(3),
                        maxLength:maxLength(40),
                        required
                    }
                },
                ar:{
                    name: {
                        minLength: minLength(3),
                        maxLength:maxLength(40),
                        required
                    }
                },

            }
        });


        const v$ = useVuelidate(rules,addDepartment.data);


        return {loading,...toRefs(addDepartment),v$};
    },
    methods: {
        editDepartment(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/department/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `global.EditSuccessfully <i class="fas fa-check-circle"></i>`,
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
