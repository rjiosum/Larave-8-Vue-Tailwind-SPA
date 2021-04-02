<template>
    <ArticleForm :heading="heading" :id="id" :edit="true" />
</template>

<script>
    import ArticleForm from "./partials/ArticleForm";

    export default {
        name: "EditArticle",
        props: {
            id: {
                type: String,
                required: true
            }
        },
        components: {
            ArticleForm
        },
        data() {
            return {
                heading: 'Update Your Article',
            }
        }
    }
</script>