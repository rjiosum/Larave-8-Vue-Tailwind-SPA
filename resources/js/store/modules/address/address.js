import * as types from './mutation-types';
import {Factory} from '@/repositories/factory';

const Address = Factory.get('address');

const state = () => ({
    addresses: [],

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

    address: {}
});

const getters = {
    addresses(state){
        return state.addresses;
    },

    address(state){
        return state.address;
    },

    pagination(state){
        return state.pagination;
    },

    filters(state){
        return state.filters;
    },

    loading(state){
        return state.loading;
    }
}

const mutations = {
    [types.SET_ADDRESSES](state, addresses){
        state.addresses = addresses;
    },

    [types.SET_ADDRESS](state, address){
        state.address = Object.assign({}, state.address, address);
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

}

const actions = {
    async setAddresses({commit}, payload){
        commit(types.SET_LOADING, true);
        const response = await Address.index(payload.params);
        if(response.status === 200){
            const {data: {data, meta}} = response;

            const addresses = data.map(item=>{
                return format(item);
            });

            commit(types.SET_ADDRESSES, addresses);

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

    async setAddress({commit}, uuid){
        commit(types.SET_LOADING, true);
        const response = await Address.show(uuid);

        if (response.status === 200) {
            const address = format(response.data.data);
            commit(types.SET_ADDRESS, address);
        }

        commit(types.SET_LOADING, false);
        return response;
    },

    setFilters({commit}, filters) {
        commit(types.SET_FILTERS, filters);
    },
}

function format(item){
    let collection = {};

    const {id: addressUuid, attributes: addressAttr} = item;
    addressAttr.uuid = addressUuid;

    const {user: {data: {id: userUuid, attributes: userDataAttr}}} = addressAttr;
    userDataAttr.uuid = userUuid;
    delete addressAttr.user;

    const {country: {data: {id: countryUuid, attributes: countryDataAttr}}} = addressAttr;
    countryDataAttr.uuid = countryUuid;
    delete addressAttr.country;

    collection = {...collection, ...addressAttr};
    collection.user = {...collection.user, ...userDataAttr};
    collection.country = {...collection.country, ...countryDataAttr};
    return collection;
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
