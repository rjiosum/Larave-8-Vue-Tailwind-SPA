export default $axios => resource => ({

    login(payload) {
        return $axios.post(`${resource}/login`, payload);
    },

    logout() {
        return $axios.post(`${resource}/logout`);
    },

    register(payload) {
        return $axios.post(`${resource}/register`, payload);
    },

    verifyEmailResend(payload) {
        return $axios.post(`${resource}/email/verification-notification`, payload);
    },

    verifyEmail(id, hash, query) {
        return $axios.get(`${resource}/verify-email/${id}/${hash}?${query}`);
    },

    sendResetPasswordLinkEmail(payload){
        return $axios.post(`${resource}/forgot-password`, payload);
    },

    resetPassword(payload){
        return $axios.post(`${resource}/reset-password`, payload);
    }
})