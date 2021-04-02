<template>
    <div class="flex justify-start flex-wrap items-center space-x-0 sm:space-x-4">
        <select class="w-16 rounded-sm" v-model="itemsPerPage" @change="handlePageSizeChange">
            <template v-for="(item, index) in items">
                <option :key="index">{{item}}</option>
            </template>
        </select>

        <Pagination
            :current="pagination.current_page"
            :total="pagination.total"
            :itemsPerPage="itemsPerPage"
            @change="handlePageChange" />

    </div>
</template>

<script>
    import Pagination from './Pagination'
    export default {
        name: "AppPagination",
        components: {
          Pagination
        },
        props: {
            store: {
                type: String,
                required: true
            },
            paginationGetter: {
                type: String,
                default: 'pagination'
            },
            filtersGetter: {
                type: String,
                default: 'filters'
            },
            filtersAction: {
                type: String,
                default: 'setFilters'
            }
        },

        data() {
            return {
                items: [4, 12, 24, 48],
                oldItemsPerPage: -1
            }
        },

        computed: {
            pagination: {
                get() {
                    return this.$store.getters[this.store + '/' + this.paginationGetter];
                }
            },
            itemsPerPage: {
                get() {
                    return this.$store.getters[this.store + '/' + this.filtersGetter].per_page;
                },
                set(value) {
                    this.oldItemsPerPage = this.itemsPerPage;
                    this.$store.dispatch(this.store + '/' + this.filtersAction, {per_page: parseInt(value)});
                }
            },
        },

        methods: {
            handlePageSizeChange() {
                if (this.oldItemsPerPage === this.itemsPerPage) {
                    return;
                }
                this.$store.dispatch(this.store + '/' + this.filtersAction, {page: 1});
                this.$emit('next');
            },

            handlePageChange(page) {
                this.$store.dispatch(this.store + '/' + this.filtersAction, {page: parseInt(page)});
                this.$emit('next');
            }
        }
    }
</script>
