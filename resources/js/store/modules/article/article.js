import router from "@/routes";
import * as types from './mutation-types';
import { Factory } from '@/repositories/factory';

const Article = Factory.get('article');
const Home = Factory.get('home');

const state = () => ({
    articles: [],

    pagination:{
        current_page: -1,
        from: -1,
        last_page:-1,
        to: -1,
        total: -1
    },

    filters: {
        page: 1,
        per_page: 12,
    },
    loading: true,
    toggleClass: 'v-grid',

    article: {},
});

const getters = {
    articles(state) {
        return state.articles;
    },

    article(state) {
        return state.article;
    },

    pagination(state) {
        return state.pagination;
    },

    filters(state) {
        return state.filters;
    },

    loading(state) {
        return state.loading;
    },

    toggleClass(state) {
        return state.toggleClass;
    }
};

const mutations = {
    [types.SET_ARTICLE_COLLECTION](state, articleCollection) {
        state.articles = articleCollection;
    },

    [types.SET_ARTICLE](state, article) {
        state.article = Object.assign({}, state.article, article);
    },

    [types.SET_FILTERS](state, filters) {
        state.filters = {...state.filters, ...filters};
    },

    [types.SET_PAGINATION](state, pagination) {
        state.pagination = {...state.pagination, ...pagination};
    },

    [types.SET_LOADING](state, payload) {
        state.loading = payload;
    },

    [types.SET_TOGGLE_CLASS](state, toggleClass) {
        state.toggleClass = toggleClass;
    },
};

const actions = {

    async setArticleCollection({commit, state, rootGetters}, payload) {

        const authUserUuid = (rootGetters['auth/user']) ? rootGetters['auth/user'].uuid : '';
        commit(types.SET_LOADING, true);

        let response;
        let viewRouteName;

        if (isHome()) {
            viewRouteName = 'home.article';
            response = await Home.index(payload.params);
        } else {
            viewRouteName = 'user.view.article';
            response = await Article.index(payload.params);
        }

        if (response.status === 200) {
            const {data: {data, meta}} = response;

            const articles = data.map(item => {
                const article = format(item);
                article.viewRouteName = (article.user.uuid === authUserUuid) ? 'user.view.article' : viewRouteName;
                article.deleted = false;
                return article;
            });

            commit(types.SET_ARTICLE_COLLECTION, articles);

            const filters = {
                page: parseInt(meta.current_page),
                per_page: parseInt(meta.per_page)
            };
            commit(types.SET_FILTERS, filters);

            const pagination = {
                current_page: parseInt(meta.current_page),
                from: parseInt(meta.from),
                last_page: parseInt(meta.last_page),
                to: parseInt(meta.to),
                total: parseInt(meta.total),
            }
            commit(types.SET_PAGINATION, pagination);

        }
        commit(types.SET_LOADING, false);
        return response;
    },

    setFilters({commit}, filters) {
        commit(types.SET_FILTERS, filters);
    },

    async setArticle({commit}, slug){
        commit(types.SET_LOADING, true);

        const response = (isHomeArticle()) ? await Home.show(slug) : await Article.show(slug);

        if (response.status === 200) {
            const article = format(response.data.data);
            commit(types.SET_ARTICLE, article);
        }
        commit(types.SET_LOADING, false);
        return response;
    },

    setToggleClass({commit}, toggleClass) {
        commit(types.SET_TOGGLE_CLASS, toggleClass);
    }

};

function format(item){
    let collection = {};

    const {id: articleUuid, attributes: articleAttr} = item;
    articleAttr.uuid = articleUuid;

    const {user: {data: {id: userUuid, attributes: userDataAttr}}} = articleAttr;
    userDataAttr.uuid = userUuid;

    delete articleAttr.user;

    collection = {...collection, ...articleAttr};
    collection.user = {...collection.user, ...userDataAttr};
    return collection;
}

function isHome(){
    return router.currentRoute.name === 'home';
}

function isHomeArticle(){
    return router.currentRoute.name === 'home.article';
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
