<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">global.Department</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    dashboard.Dashboard
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">global.Department</li>
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
                                        global.Search :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            v-if="permission.includes('department create')"
                                           :to="{name: 'createDepartment'}"
                                            class="btn btn-custom btn-warning">
                                            global.Add
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>global.Name</th>
                                        <th>global.Status</th>
                                        <th>global.Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="departments.length">
                                    <tr v-for="(item,index) in departments"  :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>
                                            <a href="#" @click="activationDepartment(item.id,item.name,item.active,index)">
                                                <span :class="[parseInt(item.active) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.active) ? 'global.Active' : 'global.Inactive'
                                                    }}</span>
                                            </a>
                                        </td>
                                        <td>

                                            <router-link
                                                :to="{name: 'editDepartment'}"
                                               v-if="permission.includes('department edit')"
                                               class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteDepartment(item.id,item.name,index)"
                                                v-if="permission.includes('department delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">global.NoDataFound</th>
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
            <Pagination :data="departmentsPaginate" @pagination-change-page="getDepartment">
                <template #prev-nav>
                    <span>&lt; Previous</span>
                </template>
                <template #next-nav>
                    <span>Next &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');

        // get packages
        let departments = ref([]);
        let departmentsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getDepartment = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/department?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    departmentsPaginate.value = l.departments;
                    departments.value = l.departments.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getDepartment();
        });

        emitter.on('get_lang', () => {
            getDepartment(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getDepartment();
            }
        });


        function deleteDepartment(id, departmentName, index) {
            Swal.fire({
                title: `global.AreYouSureDelete (${departmentName})`,
                text: `global.YouWontBeAbleToRevertThis`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/department/${id}`)
                        .then((res) => {
                            departments.value.splice(index, index + 1);

                            Swal.fire({
                                icon: 'success',
                                title: `${t("global.DeletedSuccessfully")}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `global.ThereIsAnErrorInTheSystem`,
                                text: `global.YouCanNotDelete`,
                            });
                        });
                }
            });
        }

        function activationDepartment(id, departmentName, active,index) {
            Swal.fire({
                title: `${active ? 'global.AreYouSureInactive' : 'global.AreYouSureActive'}  (${departmentName})`,
                text: `"global.YouWontBeAbleToRevertThis"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationDepartment/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? 'global.InactiveSuccessfully' :'global.ActiveSuccessfully'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            departments.value[index]['active'] =  active ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `'global.ThereIsAnErrorInTheSystem`,
                                text: `global.YouCanNotModifyThisSafe`,
                            });
                        });
                }
            });
        }

        return {departments, loading,permission, getDepartment, search, deleteDepartment, activationDepartment, departmentsPaginate};

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

</style>
