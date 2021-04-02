export default function guest ({ next, store }){
    if(store.getters['auth/isAuthenticated']){
        return next({
            name: 'user.dashboard'
        })
    }
    return next()
}