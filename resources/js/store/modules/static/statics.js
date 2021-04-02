const state = () => ({
    navs: [
        { title: 'Dashboard', link: 'user.dashboard', icon: 'fa fa-id-card' },
        { title: 'Profile', link: 'user.profile', icon: 'fa fa-user-shield' },
        { title: 'Password', link: 'user.password', icon: 'fa fa-lock' },
        { title: 'Avatar', link: 'user.avatar', icon: 'fa fa-user-circle' },
        { title: 'Articles', link: 'user.articles', icon: 'fa fa-list-ol' },
        { title: 'Addresses', link: 'user.addresses', icon: 'fa fa-envelope' },
    ],
    joinUs: [
        {text: 'Priority over non members'},
        {text: 'Able to track your dispatch'},
        {text: 'Able to retrieve your past order details & receipts'},
        {text: 'Special discount for premium members'},
    ]

});

const getters = {
    navs(state) {
        return state.navs
    },

    joinUs(state){
        return state.joinUs
    }
};


export default {
    namespaced: true,
    state,
    getters
}
