export default $axios => resource => ({

    getUser() {
        return $axios.get(`${resource}/profile`);
    },

    updateProfile(payload) {
        return $axios.patch(`${resource}/profile`, payload)
    },

    updatePassword(payload) {
        return $axios.patch(`${resource}/password`, payload)
    },

    updateAvatar(payload) {
        return $axios.post(`${resource}/avatar`, payload, { headers: { 'Content-Type': 'multipart/form-data' } })
    },

});