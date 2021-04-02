import * as types from './mutation-types'

const state = () => ({
    attributes: {
        show: false,
        msg: '',
        type: 'success'
    }
});

const getters = {
    attributes(state) {
        return state.attributes;
    }
};

const mutations = {
    [types.SET_ALERT](state, payload) {
        state.attributes = {...state.attributes, ...payload}
    }
};

const actions = {
    setAlert({commit}, payload) {
        commit(types.SET_ALERT, payload)
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
