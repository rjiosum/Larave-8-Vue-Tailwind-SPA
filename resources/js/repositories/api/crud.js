export default $axios => resource => ({

    index(request) {
        return $axios.get(`${resource}`, {params: request});
    },

    show(id) {
        return $axios.get(`${resource}/${id}`)
    },

    create(payload) {
        return $axios.post(`${resource}`, payload)
    },

    update(id, payload) {
        return $axios.patch(`${resource}/${id}`, payload)
    },

    destroy(id) {
        return $axios.delete(`${resource}/${id}`)
    }
})