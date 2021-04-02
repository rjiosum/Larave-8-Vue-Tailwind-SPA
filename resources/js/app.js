import Vue from 'vue';
import store from './store';
import router from "./routes";

Vue.config.productionTip = false;
window.EventBus = new Vue();
Vue.component('App', require('./App.vue').default);

const app = new Vue({
    el: '#app',
    router,
    store,
});
