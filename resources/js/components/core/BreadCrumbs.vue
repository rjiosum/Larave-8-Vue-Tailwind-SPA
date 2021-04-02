<template>
    <div class="py-4 px-6 mt-4 mb-12 grid grid-cols-1 rounded-sm place-items-center bg-white sm:grid-cols-2 sm:place-items-stretch sm:pb-0">
        <div class="text-xl uppercase mx-2 mb-4">{{heading}}</div>
        <div class="place-self-auto mb-0 sm:place-self-end sm:mb-4">
            <ul>
                <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'home'}">Home</router-link><i class="fa fa-angle-right"></i></li>
                <slot name="links"></slot>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BreadCrumbs",
        props:{
            heading: {
                type: String,
                required: true
            },
        }
    }
</script>

<style scoped>

</style>
