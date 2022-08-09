<template>
    <loader v-if="loading" />
    <div class="header">

        <!-- Logo -->
        <div :class="['header-left','header-left-ar']">
            <router-link :to="{name:'dashboard'}" class="logo">
                <img src="/web/img/logo.png" class="big" alt="Logo">
            </router-link>
            <router-link :to="{name:'dashboard'}" class="logo logo-small">
                <img src="/web/img/logo.png" :class="['img-ar']" alt="Logo" width="30" height="30">
            </router-link>
            <!-- Sidebar Toggle -->
            <a href="javascript:void(0);" id="toggle_btn">
                <i class="feather-chevrons-left"></i>
            </a>
            <!-- /Sidebar Toggle -->

            <!-- Mobile Menu Toggle -->
            <a class="mobile_btn" id="mobile_btn">
                <i class="feather-chevrons-left"></i>
            </a>
            <!-- /Mobile Menu Toggle -->
        </div>
        <!-- /Logo -->
        <!-- Header Menu -->
        <ul :class="['nav user-menu','user-menu-ar']">

            <!-- Notifications -->
            <Notification />
            <!-- /Notifications -->

            <!-- User Menu -->
            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <span class="user-img">
                        <img src="/admin/img/profiles/avatar-07.jpg" alt="">
                        <span class="status online"></span>
                    </span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#"><i data-feather="user" class="me-1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i data-feather="settings" class="me-1"></i> Settings</a>
                    <a class="dropdown-item" href="#" @click="logout"><i data-feather="log-out" class="me-1"></i> Logout</a>
                </div>
            </li>
            <!-- /User Menu -->

        </ul>
        <!-- /Header Menu -->

    </div>
</template>

<script>
    import Notification from './notification.vue';
    import {useStore} from "vuex";
    import {computed,ref,onMounted} from "vue";
    import Cookies from "js-cookie";

export default {
    setup(){
        const store = useStore();
        const userId = ref(0);

        let loading = computed(() => {
            return store.getters['authAdmin/loading'];
        });

        onMounted(() => {
            if (Cookies.get("tokenAdmin")){
                userId.value = JSON.parse(localStorage.getItem("user")).id
            }
        });

        function logout(){
            store.dispatch('authAdmin/logout');
        }

        return {logout,loading,userId};
    },
    components: {
        Notification
    }
}
</script>

<style>
.big {
    max-height: 90px !important;
    width: auto;
}
.mini-sidebar .header-left .logo img.img-ar{
    margin-left: 3px;
    margin-right: -22px;
}

.header .header-left{
    background: #ffeec9;
}
</style>
