<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Employees') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Employees') }}</li>
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
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            :to="{name: 'createEmployee', params: {lang: locale || 'ar'}}"
                                            v-if="permission.includes('employee create')"
                                            class="btn btn-custom btn-warning">
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Name') }}</th>
                                        <th>{{ $t('global.Phone') }}</th>
                                        <th>{{ $t('global.Email') }}</th>
                                        <th>{{ $t('global.HiringDate') }}</th>
                                        <th>{{ $t('global.Job') }}</th>
                                        <th>{{ $t('global.DepartmentForEmp') }}</th>
                                        <th>{{ $t('global.Status') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="employees.length">
                                    <tr v-for="(item,index) in employees" :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td>{{ item.user.phone }}</td>
                                        <td>{{ item.user.email }}</td>
                                        <td>{{ item.hiring_date }}</td>
                                        <td>{{ item.job.name }}</td>
                                        <td>{{ item.department.name }}</td>
                                        <td>
                                            <a href="#" @click="activationEmployee(item.id,item.user.name,item.user.status,index)">
                                                <span :class="[parseInt(item.user.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.user.status) ? $t('global.Active') : $t('global.Inactive')
                                                    }}</span>
                                            </a>
                                        </td>

                                        <td>

                                            <router-link
                                                :to="{name: 'editEmployee', params: {lang: locale || 'ar',id:item.id}}"
                                                v-if="permission.includes('employee edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <router-link
                                                :to="{name: 'changePasswordEmployee', params: {lang: locale || 'ar',id:item.id}}"
                                                v-if="permission.includes('employeeChangePassword edit')"
                                                class="btn btn-sm btn-blue me-2">
                                                <i class="fas fa-key"></i>
                                            </router-link>

                                            <a href="#" @click="deleteEmployee(item.id,item.user.name,index)"
                                            v-if="permission.includes('employee delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                             <th class="text-center" colspan="9">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :data="employeesPaginate" @pagination-change-page="getEmployee">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
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
        const {t} = useI18n({});

        let employees = ref([]);
        let employeesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getEmployee = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/employee?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    employeesPaginate.value = l.employees;
                    employees.value = l.employees.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getEmployee();
        });

        emitter.on('get_lang', () => {
            getEmployee(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getEmployee();
            }
        });


        function deleteEmployee(id, employeeName, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')} (${employeeName})`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/employee/${id}`)
                        .then((res) => {
                            employees.value.splice(index, index + 1);

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
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotDelete')}`,
                            });
                        });
                }
            });
        }

        function activationEmployee(id, employeeName, active,index) {
            Swal.fire({
                title: `${active ? t('global.AreYouSureInactive') :t('global.AreYouSureActive')}  (${employeeName})`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationEmployee/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? t('global.InactiveSuccessfully') :t('global.ActiveSuccessfully')}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            employees.value[index]['user']['status'] =  active ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotModifyThisSafe')}`,
                            });
                        });
                }
            });
        }

        return {employees, loading, getEmployee, search, deleteEmployee,permission, activationEmployee, employeesPaginate};

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
