<template>
    <div class="container mx-auto px-4">
        <ListArticles/>
    </div>
</template>

<script>
    import ListArticles from "../user/article/ListArticles";
    import { mapGetters } from "vuex";

    export default {
        name: "Home",
        components: {
            ListArticles
        },
        computed: {
            ...mapGetters({
                isAuthenticated: 'auth/isAuthenticated',
                user: 'auth/user'
            })
        }
    }
</script>

<style scoped>

</style>
