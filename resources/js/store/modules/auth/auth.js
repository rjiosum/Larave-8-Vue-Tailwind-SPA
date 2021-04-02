import { Factory } from '@/repositories/factory';
import * as types from './mutation-types';

const Auth = Factory.get('auth');
const User = Factory.get('user');

const state = () => ({
    user: null
});

const getters = {
    user(state) {
        return state.user;
    },

    isAuthenticated(state) {
        return !!state.user;
    }
};

const mutations = {
    [types.LOGIN](state, user) {
        state.user = user;
    },

    [types.LOGOUT](state) {
        state.user = null;
    }
};

const actions = {
    async login({commit, dispatch}, credentials) {
        const response = await Auth.login(credentials);
        if (response.data.status) {
            const {id, attributes} = response.data.data.data;
            attributes.uuid = id;
            commit(types.LOGIN, attributes);
        }
        return response;
    },

    async logout({commit}) {
        const response = await Auth.logout();
        if (response.data.status) {
            commit(types.LOGOUT);
        }
        return response;
    },

    async register(context, data) {
        const response = await Auth.register(data);
        if (!response.data.verify) {
            const {id, attributes} = response.data.data.data;
            attributes.uuid = id;
            context.commit(types.LOGIN, attributes);
        }
        return response;
    },

    async getUser({commit}) {
        const response = await User.getUser();
        if (response.data.status) {
            const {id, attributes} = response.data.data.data;
            attributes.uuid = id;
            commit(types.LOGIN, attributes);
        } else {
            commit(types.LOGOUT);
        }
        return response;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}


