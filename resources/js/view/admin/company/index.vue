<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">الشركات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">الشركات</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        بحث :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            v-if="permission.includes('department create')"
                                           :to="{name: 'createCompany'}"
                                            class="btn btn-custom btn-warning">
                                            اضافه
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الشركه</th>
                                        <th>الصوره</th>
                                        <th>تاريخ الانشاء</th>
                                        <th>الحاله</th>
                                        <th>الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="companies.length">
                                    <tr v-for="(item,index) in companies"  :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <th>{{dateFormat(item.created_at)}}</th>
                                        <td>
                                            <img
                                                :src="'/upload/company/' + item.media.file_name"
                                                :alt="item.name"
                                                class="custom-img"
                                            />
                                        </td>
                                        <td>
                                            <a href="#" @click="activationCompany(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                                            </a>
                                        </td>
                                        <td>

                                            <router-link
                                                :to="{name: 'editCompany',params:{id:item.id}}"
                                               v-if="permission.includes('department edit')"
                                               class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteCompany(item.id,index)"
                                                v-if="permission.includes('department delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="6">لا يوجد بيانات</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :data="companiesPaginate" @pagination-change-page="getCompany">
                <template #prev-nav>
                    <span>&lt; السابق</span>
                </template>
                <template #next-nav>
                    <span>التالي &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    setup() {

        // get packages
        let companies = ref([]);
        let companiesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getCompany = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/company?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    companiesPaginate.value = l.categories;
                    companies.value = l.categories.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getCompany();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getCompany();
            }
        });

        function deleteCompany(id, index) {
            Swal.fire({
                title: `هل تريد هذف هذا العنصر ؟ `,
                text: `لن تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/company/${id}`)
                        .then((res) => {
                            companies.value.splice(index, index + 1);

                            Swal.fire({
                                icon: 'success',
                                title: `تم الحذف بنجاح`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
                            });
                        });
                }
            });
        };

        function activationCompany(id, active,index) {
            Swal.fire({
                title: `${active ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل  ؟'} `,
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationCompany/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? 'تم التفعيل بنجاح' :'تم ايقاف التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            companies.value[index]['status'] =  active ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
                            });
                        });
                }
            });
        };

        let dateFormat = (item) => {
            return new Date(item).toDateString();
        };

        return {dateFormat,getCompany, loading,permission, search, deleteCompany, activationCompany, companiesPaginate,companies};

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin")
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 30%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.custom-img {
    width: 100px;
}
</style>
