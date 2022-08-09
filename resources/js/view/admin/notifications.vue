<template>
        <div :class="['page-wrapper','page-wrapper-ar']">
            <div class="content container-fluid">
                <loader v-if="loading" />
                <div class="row justify-content-center">
                    <div class="col-md-7 border-media">
                        <div class="notifications">
                            <div class="topnav-dropdown-header">
                                <span class="notification-title">Notifications</span>
                                <a href="#" @click.prevent="clearAll" class="clear-noti">clear</a>
                            </div>
                            <div class="noti-content" @scroll="notificationNotReadScroll" ref="scrollHeigthNotify">
                                <ul class="notification-list">

                                    <li
                                        :class="['notification-message',!notification.read_at?'read-at':'']"
                                        :key="notification.id"
                                        v-for="(notification,index) in notifications"
                                    >

                                        <!-- start trust Design -->
                                        <router-link
                                            :to="{name:notification.data.name,params: {id:notification.data.design_id}}"
                                            v-if="notification.data.name == 'showDesign'"
                                        >
                                            <div class="media d-flex" @click="clearItem(notification.id)">
                                                <span class="avatar avatar-sm flex-shrink-0">
                                                <img
                                                    class="avatar-img rounded-circle"
                                                    :src="notification.data.image"
                                                    onerror="src='/admin/img/profiles/avatar-02.jpg'"
                                                >
                                                </span>
                                                <div class="media-body flex-grow-1">
                                                    <p class="noti-details">
                                                        trustDesign
                                                    </p>
                                                    <p class="noti-time"><span class="notification-time">timeDate</span></p>
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
                        </div>

                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import adminApi from "../../api/adminAxios";
import {ref,onMounted,onBeforeMount} from "vue";

export default {
    name: "notifications",
    setup(){

        let notifications = ref([]);
        let loading = ref(false);
        let count = ref(0);
        let skip = ref(0);
        let scrollHeigthNotify = ref(null);

        let notificationNotRead = () => {
            loading.value = true;

            if(localStorage.getItem("user")){
                adminApi.get(`/v1/dashboard/getAllNot`)
                    .then((res) => {
                        notifications.value = res.data.data.Notifications;
                        count.value = res.data.data.count;
                        skip.value = 15;
                        console.log(res.data.data);
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
                    .finally(() => {
                        loading.value = false;
                    });
            }
        };

        let notificationNotReadScroll = () => {

            let el = scrollHeigthNotify.value;

            if(
                !(count.value == notifications.value.length)
                &&
                el.scrollHeight == (el.offsetHeight + el.scrollTop)
            ){
                loading.value = true;
                adminApi.get(`/v1/dashboard/getAllNot?skip=${skip.value}`)
                    .then((res) => {
                        res.data.data.Notifications.forEach(el => {
                            notifications.value.push(el);
                        });
                        skip.value += skip.value;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
                    .finally(() => {
                        loading.value = false;
                    });
            }
        };

        onBeforeMount(() => {
            notificationNotRead();
        });

        let clearItem = (id) => {
            adminApi.post(`/v1/dashboard/clearItem/${id}`)
                .then((res) => {})
                .catch((err) => {
                    console.log(err.response);
                })
        };

        let clearAll = () => {
            loading.value = true;

            adminApi.post(`/v1/dashboard/getNotNotRead`)
                .then((res) => {
                    notificationNotRead();
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        return {
            clearItem,
            clearAll,
            notificationNotRead,
            loading,
            notifications,
            count,
            notificationNotReadScroll,
            scrollHeigthNotify
        };
    }
}
</script>

<style scoped>
.notifications .noti-content {
    height: auto !important;
    width: auto !important;
}

.notifications ul.notification-list > li.read-at{
    background-color: #fcb00c24;
}

.notifications ul.notification-list > li a:hover{
    background-color: #fcb00c24;
}

.notifications .media > .avatar {
    margin: 0 10px !important;
}

.notifications {
    margin-bottom: 25px;
}

.border-media{
    border: 1px solid #fcb00c;
    padding: 25px;
    box-shadow: 0px 1px 6px 0px #fcb00c;
    border-radius: 15px;
    background-color: #fff;
}

.notifications ul.notification-list > li a {
    border-bottom: 1px solid #fcb00c4d;
    border-radius: 0px;
}

.topnav-dropdown-header .clear-noti {
    color: #fcb00c;
    font-size: 16px;
}

.page-wrapper > .content {
    min-height: 90vh;
    background-color: #fcb00c1c;
}

.topnav-dropdown-header {
    border-bottom: 1px solid #fcb00c;
    text-align: center;
}

.topnav-dropdown-header .notification-title {
    font-size: 18px;
}

.notifications .noti-content{
    max-height: 100vh;
}
</style>
