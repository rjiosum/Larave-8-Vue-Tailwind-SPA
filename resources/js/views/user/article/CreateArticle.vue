<template>
    <ArticleForm :id="id" />
</template>

<script>
    import ArticleForm from "./partials/ArticleForm";

    export default {
        name: "CreateArticle",
        components: {
            ArticleForm
        },
        data() {
            return {
                id: 'none'
            }
        },
    }
</script>