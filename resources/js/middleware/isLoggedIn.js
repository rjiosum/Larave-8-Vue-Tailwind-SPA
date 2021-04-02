export default async function isLoggedIn({next, store}) {
    if (!store.getters['auth/isAuthenticated']) {
        try {
            await store.dispatch('auth/getUser');
        } catch (e) {}
    }
    return next();
}