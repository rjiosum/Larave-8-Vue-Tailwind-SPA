<template>
    <div class="mt-10 move-in-left">

        <BreadCrumbs heading="Articles">
            <template v-slot:links>
                <li v-if="isAuthenticated" class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                <li class="inline-flex items-center"><span class="font-semibold mx-2">Articles</span></li>
            </template>
        </BreadCrumbs>

        <div class="p-4 mb-6 bg-white flex flex-wrap justify-between items-center space-x-4">
            <template v-if="isReady">
                <div class="mb-2 sm:mb-0 flex-grow"><AppPagination store="article" @next="next" /></div>
                <div class="mb-2 sm:mb-0 flex-shrink-0">
                    <label class="inline-block px-3 py-2 m-0 bg-white text-xl border-l border-t border-b border-gray-600 hover:bg-gray-600 hover:text-white cursor-pointer" :class="isGrid"><input class="hidden" type="radio" value="v-grid" v-model="toggleClass"><i class="fas fa-th"></i></label><label class="inline-block px-3 py-2 m-0 bg-white text-xl border border-gray-600 hover:bg-gray-600 hover:text-white cursor-pointer" :class="isList"><input class="hidden" type="radio" value="v-list" v-model="toggleClass"><i class="fas fa-list"></i></label>
                </div>
            </template>
            <div class="mb-2 sm:mb-0 flex-shrink-0"><router-link class="block w-full btn btn-indigo" :to="{name: 'user.create.article'}"><i class="fas fa-pen"></i> Create</router-link></div>
        </div>

        <Spinner v-if="loading" />

        <template v-if="isReady">
            <div class="grid gap-4" :class="toggleClass">
                <ListArticlesArticle
                    v-for="article in articles"
                    :key="article.uuid"
                    :article="article"
                    :toggleClass="toggleClass"
                    @delete="deleteArticle" />
            </div>
        </template>

        <div v-cloak v-if="pagination.total===0" class="text-2xl font-bold text-center">No articles found!!</div>
        <AppConfirm ref="confirm"></AppConfirm>

    </div>

</template>

<script>
    import AppConfirm from "@/components/core/AppConfirm";
    import AppPagination from "@/components/core/AppPagination";
    import handleNext from "@/mixins/handleNext";
    import { mapGetters } from "vuex";
    import { Factory } from '@/repositories/factory';
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import ListArticlesArticle from "./partials/ListArticlesArticle";
    import Spinner from "@/components/core/Spinner";

    const Article = Factory.get('article');

    export default {
        name: "ArticleList",

        components: {
            Spinner,
            BreadCrumbs,
            AppPagination,
            AppConfirm,
            ListArticlesArticle
        },
        mixins: [handleNext],
        data() {
            return {
                defaults: {
                    page: parseInt(this.$route.query.page) || 1,
                    per_page: parseInt(this.$route.query.per_page) || 12
                },
            }
        },

        methods: {

            fetch(params) {
                this.$store.dispatch("article/setArticleCollection", {params})
            },
            async deleteArticle(article) {
                const confirm = await this.$refs.confirm.open('Delete', 'Are you sure?');
                if (confirm) {
                    try {
                        const {data} = await Article.destroy(article.uuid);

                        if (data.status) {
                            await this.$store.dispatch('notify/setNotice', {
                                show: true,
                                msg: data.message,
                                color: "bg-red-500",
                                icon: "fa-exclamation-triangle"
                            });
                            this.fetch(this.defaults);
                        }

                    } catch (error) {
                        //console.log(error);
                    }
                }
            }
        },

        created() {
            this.fetch(this.defaults);
        },

        computed: {
            isList(){
                return (this.toggleClass === 'v-list') ? 'bg-gray-900 text-gray-50' : '';
            },
            isGrid(){
                return (this.toggleClass === 'v-grid') ? 'bg-gray-900 text-gray-50' : '';
            },

            isReady(){
                return this.pagination.total > 0 && !this.loading;
            },

            ...mapGetters({
                articles: "article/articles",
                pagination: 'article/pagination',
                filters: 'article/filters',
                loading: 'article/loading',
                isAuthenticated: 'auth/isAuthenticated',
                user: 'auth/user',
            }),

            toggleClass: {
                get() {
                    return this.$store.getters["article/toggleClass"];
                },
                set(value) {
                    this.$store.dispatch("article/setToggleClass", value);
                }
            }
        }

    }
</script>
