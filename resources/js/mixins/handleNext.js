export default {
    methods: {
        next(params = {}) {
            const merged = {...this.filters, ...params};
            //this.fetch(merged);
            this.$router.push({path: this.$route.path, query: merged});
        },
    }
}
