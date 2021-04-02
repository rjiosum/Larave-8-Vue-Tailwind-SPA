<template>
    <button class="px-3 py-2 fixed right-4 bottom-4 text-2xl bg-indigo-500 text-white shadow-md rounded-sm hover:bg-gray-800 focus:outline-none focus:ring-1 focus:ring-indigo-500" v-show="fab" @click="toTop">
        <i class="fa fa-chevron-up"></i>
    </button>
</template>

<script>
    export default {
        name: "AppToTop",
        data: () => ({
            fab: false,
        }),
        methods: {
            toTop() {
                window.scrollTo({top: 0, behavior: 'smooth'});
            },
            scrollListener(){
                this.fab = window.pageYOffset > 200
            }
        },
        mounted(){
            window.addEventListener('scroll', this.scrollListener);
        },
        beforeDestroy() {
            window.removeEventListener('scroll', this.scrollListener);
        }
    }
</script>

<style scoped>

</style>
