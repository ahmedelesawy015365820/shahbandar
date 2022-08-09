<template>
<!-- Sidebar -->
    <div :class="['sidebar sidebar-ar']" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li :class="[$route.name == 'dashboard'? 'active': '']">
                        <router-link :to="{name:'dashboard'}" >
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span></span>
                        </router-link>
                    </li>


                    <li class="submenu" v-if="permission.includes('management')">
                        <a href="#" ><i class="fas fa-suitcase"></i> <span>Management</span> <span :class="['menu-arrow','menu-arrow-ar']"></span></a>
                        <ul>
                            <li :class="[$route.name == 'indexDepartment'? 'active': '']" v-if="permission.includes('department read')">
                                <router-link :to="{name:'indexDepartment'}" :class="['sidebar-menu-rtl']">
                                    Department
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexJob'? 'active': '']" v-if="permission.includes('job read')">
                                <router-link :to="{name:'indexJob'}" :class="['sidebar-menu-rtl']">
                                    Jobs
                                </router-link>
                            </li>
                        </ul>
                    </li>


                    <li class="submenu" v-if="permission.includes('role-employee')">
                        <a href="#" ><i class="fas fa-user-tie"></i> <span> Employees</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li :class="[$route.name == 'indexRole'? 'active': '']" v-if="permission.includes('role read')">
                                <router-link :to="{name:'indexRole'}" :class="['sidebar-menu-rtl']">
                                    Roles
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexEmployee'? 'active': '']" v-if="permission.includes('employee read')">
                                <router-link :to="{name:'indexEmployee'}" :class="['sidebar-menu-rtl']">
                                    Employees
                                </router-link>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->
</template>

<script>
import {ref,onMounted,reactive,computed} from 'vue';
import {useStore} from 'vuex';

export default {
   setup(){

        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        onMounted(() => {
            var $slimScrolls = $('.slimscroll');

            // Sidebar Slimscroll
            if ($slimScrolls.length > 0) {
                $slimScrolls.slimScroll({
                    height: 'auto',
                    width: '100%',
                    position: 'right',
                    size: '7px',
                    color: '#ccc',
                    allowPageScroll: false,
                    wheelStep: 10,
                    touchScrollStep: 100
                });
                var wHeight = $(window).height() - 60;
                $slimScrolls.height(wHeight);
                $('.sidebar .slimScrollDiv').height(wHeight);
                $(window).resize(function () {
                    var rHeight = $(window).height() - 60;
                    $slimScrolls.height(rHeight);
                    $('.sidebar .slimScrollDiv').height(rHeight);
                });
            }
        });


        return {permission};
   }
}


window.onload = (event) => {
    var Sidemenu = function () {
        this.$menuItem = $('#sidebar-menu a');
    };
    function init() {
    var $this = Sidemenu;
        $('#sidebar-menu a').on('click', function (e) {
            if ($(this).parent().hasClass('submenu')) {
                e.preventDefault();
            }
            if (!$(this).hasClass('subdrop')) {
                $('ul', $(this).parents('ul:first')).slideUp(350);
                $('a', $(this).parents('ul:first')).removeClass('subdrop');
                $(this).next('ul').slideDown(350);
                $(this).addClass('subdrop');
            } else if ($(this).hasClass('subdrop')) {
                $(this).removeClass('subdrop');
                $(this).next('ul').slideUp(350);
            }
        });
        $('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
    }

// Sidebar Initiate
    init();

};

</script>

<style>
.sidebar-ar {
    left: unset;
    right: 0;
}
.sidebar .sidebar-menu > ul > li > a span {
    margin-right: 10px;
}

.sidebar-menu li a{
    color: #000;
}

.sidebar-menu li a:hover{
    color: #fcb00c !important;
}

.sidebar-menu li.active > a{
    color: #fcb00c !important;
}

.menu-title {
    color: #fcb00c !important;
}

.show{
    display: block;
}
.sidebar{
    background-color: #fcb00c38;
}

.sidebar-menu .menu-arrow.menu-arrow-ar{
    left: 15px;
    right: unset;
}
.sidebar-menu-rtl{
    padding: 7px 45px 7px 10px!important;
}
.padding-en{
    padding: 7px 10px 7px 32px !important;
}
.drop-child{
    padding: none !important;
}
.drop-child span{
    float: none !important;
}
.t-right{
    text-align: right !important;
}

</style>
