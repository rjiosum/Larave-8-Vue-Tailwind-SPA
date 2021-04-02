import * as types from './mutation-types'

const state = () => ({
    notice: {
        show: false,
        msg: '',
        color: 'bg-green-600',
        icon: 'fa-check',
        timeout: 6000
    }
});

const getters = {
    notice(state) {
        return state.notice;
    }
};

const mutations = {
    [types.SET_NOTICE](state, payload) {
        state.notice = {...state.notice, ...payload}
    }
};

const actions = {
    setNotice({commit}, payload) {
        commit(types.SET_NOTICE, payload)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
