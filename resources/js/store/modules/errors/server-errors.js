import * as types from './mutation-types';

const state = () => ({
    errors: {}
});

const getters = {
    errors(state) {
        return state.errors;
    }
};

const mutations = {
    [types.SET_ERRORS](state, errors) {
        state.errors = errors;
    }
};

const actions = {
    setErrors({commit}, errors) {
        commit(types.SET_ERRORS, errors);
    },
    clearErrors({commit}) {
        commit(types.SET_ERRORS, {});
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}