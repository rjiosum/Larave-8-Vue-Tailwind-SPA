<template>
    <div class="mt-10 move-in-right">
        <slot name="breadcrumbs"></slot>
        <div class="w-full sm:w-5/6 md:w-5/6 lg:w-1/2 mx-auto">
            <div class="mb-4 font-bold text-2xl tracking-wide text-center">{{heading}}</div>
            <div class="p-4 bg-white">
                <div class="flex justify-start items-center space-x-6">
                    <div class="w-12 h-12 overflow-hidden rounded-full shadow-md">
                        <img class="w-full h-full object-cover" :src="user.avatar" alt="avatar" />
                    </div>
                    <div>
                        <div class="text-gray-500 font-semibold text-xl tracking-wide">{{user.name}}</div>
                        <div class="text-gray-400 font-medium text-sm tracking-wide">{{user.email}}</div>
                    </div>
                </div>
                <hr class="my-6">
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserManagementLayout",
    props:{
        heading: {
            type: String,
            required: true
        },
        user:{
            type: Object,
            required: true
        }
    }
}
</script>
