<template>
    <div class="container mx-auto px-4">
        <ViewArticle :slug="slug" />
    </div>
</template>

<script>
    import ViewArticle from "../user/article/ViewArticle";

    export default {
        name: "Article",
        props:{
          slug: {
              type: String,
              required: true,
          }
        },
        components:{
            ViewArticle
        }
    }
</script>

<style scoped>

</style>
