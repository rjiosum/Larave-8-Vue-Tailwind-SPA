<template>
    <div class="mt-10 move-in-left">

        <BreadCrumbs heading="Dashboard">
            <template v-slot:links>
                <li class="inline-flex items-center"><span class="font-semibold mx-2">Dashboard</span></li>
            </template>
        </BreadCrumbs>

        <div class="p-4 mb-10 bg-white text-gray-500 rounded-sm">From your "My Account Dashboard" you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <template v-for="(item, index) in items">
                <div :key="`d-item-${index}`" class="bg-white p-4 pb-8 text-center text-gray-500 space-y-6 rounded-sm">
                    <div class="text-xl font-semibold tracking-wide">
                        <i :class="item.icon"></i>
                        <span>{{item.header}}</span>
                    </div>
                    <div class="text-sm font-medium">{{item.text}}</div>
                    <div class="mb-4">
                        <router-link class="btn btn-indigo capitalize text-sm" :to="{name: item.link}">Browse</router-link>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    export default {
        name: "UserDashboard",
        components: {BreadCrumbs},
        data(){
            return {
                items: [
                    {icon: 'fa fa-share-alt-square', header: 'Share', text: 'Share some article, thoughts or quotes. Here you can create, update or delete old articles, thoughts or quotes.', link: 'user.articles'},
                    {icon: 'fa fa-user-circle', header: 'Profile', text: 'Manage your profile. Here, you can update your account information.', link: 'user.profile'},
                    {icon: 'fa fa-unlock', header: 'Password', text: 'Change your password. Here, you can change your password.', link: 'user.password'},
                    {icon: 'fa fa-camera', header: 'Avatar', text: 'Change your avatar. Here, you can change your avatar.', link: 'user.avatar'},
                    {icon: 'fa fa-envelope', header: 'Address Book', text: 'Manage your address. Here, you can add new address or update an existing ones.', link: 'user.addresses'},
                ]
            }
        }
    }
</script>



