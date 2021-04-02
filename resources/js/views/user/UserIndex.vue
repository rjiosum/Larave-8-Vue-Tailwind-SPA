<template>
    <div class="container mx-auto px-4">
        <router-view></router-view>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        name: "User",
        computed: {
            ...mapGetters({
                navs: 'statics/navs'
            })
        }

    }
</script>

<style scoped>

</style>
