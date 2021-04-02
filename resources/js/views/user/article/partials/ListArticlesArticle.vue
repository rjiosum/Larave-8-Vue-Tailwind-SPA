<template>
    <div class="flex flex-col p-4 bg-white rounded-sm move-in-right">
        <div class="flex justify-between items-start space-x-4">
            <div class="text-gray-500">
                <div class="font-medium mb-2">{{article.title}}</div>
                <span class="text-gray-400 text-sm"><i class="far fa-clock"></i> Submitted {{article.created_h}}</span>
            </div>
            <div class="flex-shrink" :class="[(toggleClass==='v-list') && 'sm:flex-shrink-0', (toggleClass==='v-grid') && 'flex flex-col']">
                <router-link class="mb-2 w-10 h-10 inline-flex justify-center items-center rounded-full shadow-md text-white bg-indigo-600 hover:bg-indigo-800" title="View Details" :to="{name: article.viewRouteName, params:{slug: article.slug}}"><i class="far fa-list-alt"></i></router-link>
                <template v-if="authorized(article.user.uuid)">
                    <router-link class="mb-2 w-10 h-10 inline-flex justify-center items-center rounded-full shadow-md text-white bg-green-600 hover:bg-green-800" title="Edit" :to="{name: 'user.edit.article', params:{id: article.uuid}}"><i class="fas fa-save"></i></router-link>

                    <button :dusk="article.uuid" class="w-10 h-10 inline-flex justify-center items-center rounded-full shadow-md text-white bg-red-600 hover:bg-red-800" @click="deleteArticle(article)"><i class="fas fa-trash-alt"></i></button>
                </template>
            </div>
        </div>

        <div class="my-6">
            <div class="text-gray-500">{{description(article.description)}} <router-link class="font-medium text-sm text-indigo-500 hover:text-indigo-700" :to="{name: article.viewRouteName, params:{slug: article.slug}}">Read More</router-link></div>
        </div>

        <div class="mt-auto flex justify-between items-center space-x-4">
            <div class="w-16 h-16 overflow-hidden rounded-full shadow-lg"><img class="w-full h-full object-cover transition-all duration-300 ease-in-out transform hover:scale-150" :src="article.user.avatar" alt="user avatar" /></div>
            <button class="px-4 py-1 text-sm whitespace-nowrap cursor-default text-indigo-500 border border-indigo-500 rounded-full focus:outline-none"><span class="mr-1 inline-block">{{article.user.first_name}}</span> <i class="far fa-user"></i></button>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        name: "ListArticlesArticle",
        props:{
            article: {
                type: Object,
                required: true
            },
            toggleClass: {
                type: String,
                required: true
            }
        },
        methods: {
            description(text) {
                let body = this.stripTags(text);
                return body.length > 300 ? body.substring(0, 300) + '...' : body;
            },

            stripTags(text) {
                return text.replace(/(<([^>]+)>)/ig, '');
            },

            deleteArticle(article) {
                this.$emit('delete', article);
            },

            authorized(articleUserId) {
                const userId = (this.user) ? this.user.uuid : 'xxx';
                return (userId === articleUserId);
            },
        },
        computed:{
            ...mapGetters({
                user: 'auth/user'
            })
        }

    }
</script>

<style scoped>

</style>
