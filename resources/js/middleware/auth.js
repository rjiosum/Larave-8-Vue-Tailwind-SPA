export default function auth ({ next, store }){
    if(!store.getters['auth/isAuthenticated']){
        return next({
            name: 'auth.login'
        });
    }
    return next();
}