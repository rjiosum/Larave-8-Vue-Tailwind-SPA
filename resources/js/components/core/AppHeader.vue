<template>
    <header class="px-4 py-3 bg-white fixed top-0 left-0 w-full shadow-md z-50">
        <div class="flex justify-between items-center">
            <div>
                <router-link :to="{name: 'home'}" class="flex justify-between items-center">
                    <img class="inline-block h-10" :src="logo" alt="logo" />
                    <span class="mx-1 inline-block uppercase font-bold text-indigo-500">spa</span><span class="inline-block uppercase font-bold text-gray-400 tracking-wide">starterkit</span>
                </router-link>
            </div>

            <div>
                <template v-if="!isAuthenticated">
                    <div class="relative">
                        <button @click="isNavOpen = !isNavOpen" class="px-2 py-1 block sm:hidden text-2xl bg-indigo-500 text-white z-40 hover:bg-indigo-700 focus:outline-none"><i class="fa fa-bars"></i></button>
                        <button @click="isNavOpen=false" v-if="isNavOpen" class="fixed inset-0 w-full h-full cursor-default -z-1" tabindex="-1"></button>
                        <div :class="isNavOpen ? 'slide-down' : 'slide-up sm:slide-up'" class="w-48 absolute top-12 right-0 bg-white shadow-md z-40 sm:w-full sm:relative sm:top-auto sm:right-auto sm:shadow-none sm:flex sm:justify-between sm:items-center">
                                <router-link class="block px-4 py-2 text-gray-400 font-normal hover:bg-indigo-600 hover:text-white" :to="{name: 'auth.login'}"><span class="flex justify-start items-center space-x-2"><i class="fa fa-lock"></i><span>Login</span></span></router-link>
                                <hr class="block sm:hidden">
                                <router-link class="block px-4 py-2 text-gray-400 font-normal hover:bg-indigo-600 hover:text-white" :to="{name: 'auth.register'}"><span class="flex justify-start items-center space-x-2"><i class="fa fa-user"></i><span>Register</span></span></router-link>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="relative">
                        <button @click="isAccountNavOpen = !isAccountNavOpen" class="flex justify-center items-center space-x-2 text-gray-400 text-sm z-40 hover:text-indigo-600 focus:outline-none" id="dropdown-toggle">
                            <span class="inline-block w-10 h-10 rounded-full overflow-hidden shadow-md"><img class="w-full h-full object-cover" :src="user.avatar" :alt="user.name" /></span><span class="hidden sm:inline-block">{{user.name}}</span><i class="fa fa-chevron-down"></i>
                        </button>

                        <button @click="isAccountNavOpen=false" v-if="isAccountNavOpen" class="fixed inset-0 w-full h-full cursor-default -z-1" tabindex="-1"></button>

                        <div class="w-48 absolute top-12 right-0 bg-white shadow-md z-40" :class="isAccountNavOpen ? 'slide-down' : 'slide-up'" id="dropdown-menu-content">
                                 <router-link class="block px-4 py-2 text-gray-400 font-normal hover:bg-indigo-600 hover:text-white" v-for="(nav, index) in navs" :key="`navs-${index}`" :to="{name: nav.link}"><i :class="nav.icon"></i><span class="inline-block ml-4">{{nav.title}}</span></router-link>
                                <hr/>
                                <router-link class="block px-4 py-2 text-gray-400 font-normal hover:bg-indigo-600 hover:text-white" :to="{name: 'auth.logout'}"><i class="fa fa-door-open"></i><span class="inline-block ml-4">Logout</span></router-link>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </header>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: "AppHeader",

        watch: {
            '$route' () {
                this.isAccountNavOpen = false;
                this.isNavOpen = false;
            }
        },

        data(){
            return{
                logo: process.env.MIX_APP_URL + '/frontend/images/logo.png',
                isAccountNavOpen: false,
                isNavOpen: false,
            }
        },

        computed: {
            ...mapGetters({
                isAuthenticated: 'auth/isAuthenticated',
                user: 'auth/user',
                navs: 'statics/navs'
            })
        },

        created() {
            EventBus.$on('kick', () => {
                this.$store.dispatch('auth/logout')
                    .then((response) => {
                        this.$store.dispatch('notify/setNotice', {
                            show: true,
                            msg:"Logged out successfully!!",
                            color: "bg-indigo-500",
                            icon: "fa-info"
                        });
                        this.$router.back();
                    })
                    .catch((error) => {
                        window.location = '/login';
                    });
            });
        }
    }
</script>

<style scoped>

</style>
