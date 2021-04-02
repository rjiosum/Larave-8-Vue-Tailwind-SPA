import Vue from 'vue';
import Vuex from 'vuex';

import errors from './modules/errors/server-errors'
import notify from "./modules/notify/notify"
import auth from "./modules/auth/auth"
import statics from "./modules/static/statics";
import article from "./modules/article/article";
import alert from "./modules/alert/alert";
import address from "./modules/address/address";


Vue.use(Vuex);

export default new Vuex.Store({
    modules:{
        errors,
        notify,
        alert,
        auth,
        statics,
        article,
        address
    }
});
