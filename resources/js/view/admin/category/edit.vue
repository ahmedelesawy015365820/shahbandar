<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">الموردين</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSupplier'}">الموريد</router-link></li>
                            <li class="breadcrumb-item active">تعديل مورد</li>
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
                                    :to="{name: 'indexSupplier'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editSupplier" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم المورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name_supplier.$model"
                                                       id="validationCustom01"
                                                       placeholder="اسم المورد"
                                                       :class="{'is-invalid':v$.name_supplier.$error,'is-valid':!v$.name_supplier.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name_supplier.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.name_supplier.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name_supplier.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.name_supplier.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.name_supplier.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">عنوان المورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       id="validationCustom02"
                                                       placeholder="عنوان المورد"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.address.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.address.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.address.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.address.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">تليفون المورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.phone_supplier.$model"
                                                       id="validationCustom03"
                                                       placeholder="تليفون المورد"
                                                       :class="{'is-invalid':v$.phone_supplier.$error,'is-valid':!v$.phone_supplier.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone_supplier.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.phone_supplier.integer.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                    <span v-if="v$.phone_supplier.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.phone_supplier.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.phone_supplier.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.phone_supplier.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">رقم السجل التجاري للمورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.commercial_record.$model"
                                                       id="validationCustom04"
                                                       placeholder="رقم السجل التجاري للمورد"
                                                       :class="{'is-invalid':v$.commercial_record.$error,'is-valid':!v$.commercial_record.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.commercial_record.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.commercial_record.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">رقم البطاقة الضريبية للمورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.tax_card.$model"
                                                       id="validationCustom06"
                                                       placeholder="رقم البطاقة الضريبية للمورد"
                                                       :class="{'is-invalid':v$.tax_card.$error,'is-valid':!v$.tax_card.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.tax_card.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.tax_card.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">اسم المسئول لدي المورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom07"
                                                       placeholder="اسم المسئول لدي المورد"
                                                       :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom09">رقم تليفون المسئول لدي المورد</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       id="validationCustom09"
                                                       placeholder="رقم تليفون المسئول لدي المورد "
                                                       :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.integer.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                    <span v-if="v$.phone.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.phone.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>


                                        </div>

                                        <button class="btn btn-primary" type="submit">تعديل</button>
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
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
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
        let loading = ref(false);


        let getSupplier = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/supplier/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addSupplier.data.name_supplier = l.supplier.name_supplier;
                    addSupplier.data.address = l.supplier.address;
                    addSupplier.data.phone_supplier = l.supplier.phone_supplier;
                    addSupplier.data.commercial_record = l.supplier.commercial_record;
                    addSupplier.data.name = l.supplier.name;
                    addSupplier.data.phone = l.supplier.phone;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getSupplier();
        });


        //start Supplier
        let addSupplier =  reactive({
            data:{
                name_supplier : '',
                address : '',
                phone_supplier : '',
                commercial_record : '',
                tax_card : '',
                name : '',
                phone : '',
                _method : 'PUT',
            }
        });

        const rules = computed(() => {
            return {
                name_supplier: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                address: {
                    minLength: minLength(3),
                    maxLength:maxLength(100),
                    required
                },
                phone_supplier: {
                    minLength: minLength(4),
                    maxLength:maxLength(15),
                    integer,
                    required
                },
                commercial_record: {
                    maxLength:maxLength(100),
                },
                tax_card: {
                    maxLength:maxLength(100),
                },
                name: {
                    maxLength:maxLength(100),
                },
                phone: {
                    maxLength:maxLength(15),
                    integer
                },
            }
        });


        const v$ = useVuelidate(rules,addSupplier.data);


        return {loading,...toRefs(addSupplier),v$};
    },
    methods: {
        editSupplier(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/supplier/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
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
