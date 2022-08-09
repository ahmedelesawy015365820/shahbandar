<template>
    <div :class="['main-wrapper',link.includes($route.name) ? 'login-body' : '']">

			<!-- Header -->
            <admin-header v-if="!link.includes($route.name)" />

			<!-- /Header -->

			<!-- Sidebar -->
            <sidebar v-if="!link.includes($route.name)" />

            <router-view v-slot="{ Component }">
                <transition name="scale" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
			<!-- /Sidebar -->

		<!-- /Main Wrapper -->
	</div>
</template>

<script>
    import adminHeader from "./components/admin/general/adminHeader";
    import sidebar from "./components/admin/general/Sidebar";

    export default {
        mounted() {
        },
        components:{
            adminHeader,
            sidebar,
        },
        data(){
            return {
                link : ['loginLang','login','Page404','forgetPassword','resetPassword']
            }
        }
    }
</script>

<style scope>

.scale-enter-active,
.scale-leave-active {
    transition: all 0.5s ease;
}


.scale-enter-from,
.scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}

</style>
