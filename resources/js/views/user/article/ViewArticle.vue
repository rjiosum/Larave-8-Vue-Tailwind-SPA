<template>
    <div class="mt-10 move-in-left">

        <BreadCrumbs heading="Dashboard">
            <template v-slot:links>
                <template v-if="isAuthenticated">
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.articles'}">Articles</router-link><i class="fa fa-angle-right"></i></li>
                </template>
                <li class="inline-flex items-center"><span class="font-semibold mx-2">Article Detail</span></li>
            </template>
        </BreadCrumbs>

        <template v-if="!loading">
            <div class="move-in-right">
                <div class="p-4 mb-6 bg-white">
                    <div class="mb-4 font-bold text-xl text-gray-600 tracking-wide">{{article.title}}</div>
                    <div class="text-gray-400 text-sm">
                        <i class="far fa-clock"></i> Submitted {{article.created_h}}
                    </div>
                </div>

                <div class="p-4 mb-6 bg-white">
                    <div v-html="article.description"></div>
                    <hr class="my-6">
                    <div class="flex justify-between items-center space-x-4">
                        <div class="w-16 h-16 overflow-hidden rounded-full shadow-lg"><img class="w-full h-full object-cover transition-all duration-300 ease-in-out transform hover:scale-150" :src="article.user.avatar" alt="user avatar" /></div>
                        <button class="px-4 py-1 whitespace-nowrap cursor-default text-indigo-500 border border-indigo-500 rounded-full focus:outline-none"><span class="mr-1 inline-block">{{article.user.first_name}}</span> <i class="far fa-user"></i></button>
                    </div>
                </div>

                <div class="text-right mt-8">
                    <button class="btn btn-red w-auto" @click="back">Back</button>
                </div>
            </div>
        </template>

        <!--<div class="text-center" v-if="loading">
            <v-progress-circular
                :size="100"
                :width="15"
                color="purple"
                indeterminate
            ></v-progress-circular>
        </div>-->

    </div>
</template>

<script>
    import goBack from "@/mixins/goBack";
    import { mapGetters } from "vuex";
    import BreadCrumbs from "@/components/core/BreadCrumbs";

    export default {
        name: "ViewArticle",

        props: {
            slug: {
                type: String,
                required: true
            }
        },

        components: {
            BreadCrumbs
        },

        mixins: [goBack],

        created() {
            this.fetch();
        },

        methods: {
            async fetch() {
                await this.$store.dispatch('article/setArticle', this.slug);
            },
        },
        computed: {
            ...mapGetters({
                article: 'article/article',
                loading: 'article/loading',
                isAuthenticated: 'auth/isAuthenticated',
            }),
        }
    }
</script>
