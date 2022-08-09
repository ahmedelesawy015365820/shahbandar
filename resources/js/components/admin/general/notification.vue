<template>
    <li class="nav-item dropdown">
        <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <i class="feather-bell"></i> <span v-if="count" class="badge badge-pill">{{ count }}</span>
        </a>
        <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header" v-if="count">
                <span class="notification-title">{{$t("notification.Notifications")}}</span>
                <a href="#" @click.prevent="clearAll" class="clear-noti">{{$t("notification.clear")}}</a>
            </div>
            <div class="noti-content" v-if="count">
                <ul class="notification-list">

                    <li class="notification-message" :key="index" v-for="(notification,index) in notifications">

                        <!-- start trust Design -->
                        <router-link
                            :to="{name:notification.data.name,params: {id:notification.data.design_id}}"
                            v-if="notification.data.name == 'showDesign'"
                        >
                            <div class="media d-flex" @click="clearItem(notification.id,index)">
                                        <span class="avatar avatar-sm flex-shrink-0">
                                            <img
                                                class="avatar-img rounded-circle"
                                                :src="notification.data.image"
                                                onerror="src='/admin/img/profiles/avatar-02.jpg'"
                                            >
                                        </span>
                                <div class="media-body flex-grow-1">
                                    <p class="noti-details">
                                        {{`${$t("notification.trustDesign")}
                                        ${notification.data.name_user}`}}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">{{notification.data.timeDate}}</span></p>
                                </div>
                            </div>
                        </router-link>
                        <!-- end trust Design -->

                         <!-- start trust company -->
                        <router-link
                            :to="{name:notification.data.name,params: {id:notification.data.company_id}}"
                            v-else-if="notification.data.name == 'showCompany'"
                        >
                            <div class="media d-flex" @click="clearItem(notification.id,index)">
                                        <span class="avatar avatar-sm flex-shrink-0">
                                            <img
                                                class="avatar-img rounded-circle"
                                                :src="notification.data.image"
                                                onerror="src='/admin/img/profiles/avatar-02.jpg'"
                                            >
                                        </span>
                                <div class="media-body flex-grow-1">
                                    <p class="noti-details">
                                        {{`${$t("notification.trustCompany")}
                                        ${notification.data.name_user}`}}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">{{notification.data.timeDate}}</span></p>
                                </div>
                            </div>
                        </router-link>
                        <!-- end trust company -->

                        <!-- start trust client -->
                        <router-link
                            :to="{name:notification.data.name,params: {id:notification.data.client_id}}"
                            v-else-if="notification.data.name == 'showClient'"
                        >
                            <div class="media d-flex" @click="clearItem(notification.id,index)">
                                        <span class="avatar avatar-sm flex-shrink-0">
                                            <img
                                                class="avatar-img rounded-circle"
                                                :src="notification.data.image"
                                                onerror="src='/admin/img/profiles/avatar-02.jpg'"
                                            >
                                        </span>
                                <div class="media-body flex-grow-1">
                                    <p class="noti-details">
                                        {{`${$t("notification.trustClient")}
                                        ${notification.data.name_user}`}}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">{{notification.data.timeDate}}</span></p>
                                </div>
                            </div>
                        </router-link>
                        <!-- end trust client -->

                        <!-- start trust support -->
                        <router-link
                            :to="{name:notification.data.name,params: {id:notification.data.support_id}}"
                            v-else-if="notification.data.name == 'showSupport'"
                        >
                            <div class="media d-flex" @click="clearItem(notification.id,index)">
                                <span class="avatar avatar-sm flex-shrink-0">
                                    <span class="avatar-title rounded-circle bg-primary-light"><i class="far fa-user"></i></span>
                                </span>
                                <div class="media-body flex-grow-1">
                                    <p class="noti-details">
                                        {{`${$t("notification.support")}`}}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">{{notification.data.timeDate}}</span></p>
                                </div>
                            </div>
                        </router-link>
                        <!-- end trust support -->

                    </li>
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <router-link
                    :to="{name:'notification'}"
                >
                    all
                </router-link>
            </div>
        </div>
    </li>
</template>

<script>
import {ref,onMounted} from 'vue';
import adminApi from "../../../api/adminAxios";

export default {
    name: "notification",
    setup(){

        let notifications = ref([]);
        let count = ref(0);

        let notificationNotRead = () => {
            if(localStorage.getItem("user")){
                adminApi.get(`/v1/dashboard/getNotNotRead`)
                    .then((res) => {
                        notifications.value = res.data.data.Notifications;
                        count.value = res.data.data.count;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        let clearItem = (id,index) => {
            if(localStorage.getItem("user")){
                adminApi.post(`/v1/dashboard/clearItem/${id}`)
                    .then((res) => {
                        notifications.value.splice(index,index + 1);
                        count.value -= 1;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        let clearAll = () => {
            if(localStorage.getItem("user")){
                adminApi.post(`/v1/dashboard/getNotNotRead`)
                    .then((res) => {
                        notifications.value = [];
                        count.value = 0;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        onMounted(() => {
            notificationNotRead();
        });

        // if(localStorage.getItem("user")){
        //     Echo.private('App.Models.User.'+JSON.parse(localStorage.getItem("user")).id)
        //         .notification((notification) => {
        //             console.log(notification);
        //             notifications.value.unshift(notification);
        //             count.value += 1;
        //             VanillaToasts.create({
        //                 // notification title
        //                 title:'sadasdsaasd',
        //
        //                 // notification message
        //                 text:'fgvbsdg dsdf sdf sdf sd sdf',
        //
        //                 // success, info, warning, error   / optional parameter
        //                 type: 'warning',
        //
        //                 timeout:10000,
        //
        //                 // path to notification icon
        //                 icon: '' || "/web/img/user/3/1655989450.img-6.png",
        //
        //                 // topRight, topLeft, topCenter, bottomRight, bottomLeft, bottomCenter
        //                 positionClass: 'topLeft',
        //
        //                 // auto dismiss after 5000ms
        //
        //             });
        //         });
        // }

        return {notifications,count,clearItem,clearAll};
    }
}
</script>

<style scoped>
.notifications .media > .avatar {
    margin: 0 10px !important;
}
</style>
