<template>
    <ul class="flex items-center justify-center space-x-2">
        <li v-if="hasPrev()">
            <button class="w-10 h-10 inline-flex items-center justify-center font-bold bg-white text-gray-600 rounded-full shadow-sm hover:text-white hover:bg-indigo-500 border focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    @click.prevent="change(prevPage)"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
        </li>

        <li v-if="hasFirst()">
            <button class="w-10 h-10 inline-flex items-center justify-center font-bold bg-white text-gray-600 rounded-full shadow-sm hover:text-white hover:bg-indigo-500 border focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    @click.prevent="change(1)"
            >1</button>
        </li>

        <li v-if="hasFirst()">...</li>

        <li v-for="page in pages" :key="page">
            <button :class="[page === current ? 'text-white bg-indigo-500' : 'text-gray-600 hover:text-white hover:bg-indigo-500', 'w-10 h-10 inline-flex items-center justify-center font-bold bg-white rounded-full shadow-sm border focus:outline-none focus:ring-1 focus:ring-indigo-500']"
                    @click.stop="change(page)"
                    :disabled="page === current"
            >{{ page }}</button>
        </li>

        <li v-if="hasLast()">...</li>

        <li v-if="hasLast()">
            <button class="w-10 h-10 inline-flex items-center justify-center font-bold bg-white text-gray-600 rounded-full shadow-sm hover:text-white hover:bg-indigo-500 border focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    @click.prevent="change(totalPages)"
            >{{totalPages}}</button>
        </li>

        <li v-if="hasNext()">
            <button class="w-10 h-10 inline-flex items-center justify-center font-bold bg-white text-gray-600 rounded-full shadow-sm hover:text-white hover:bg-indigo-500 border focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    @click.prevent="change(nextPage)"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "Pagination",
        props: {
            total: {
                type: Number,
                required: true
            },
            current: {
                type: Number,
                required: true
            },
            itemsPerPage: {
                type: Number,
                required: true
            },
            pageRange: {
                type: Number,
                default: 1
            }
        },

        computed: {
            pages() {
                let pages = []
                for (let i = this.rangeStart; i <= this.rangeEnd; i++) {
                    pages.push(i)
                }
                return pages
            },
            rangeStart() {
                let start = this.current - this.pageRange
                return (start > 0) ? start : 1
            },
            rangeEnd() {
                let end = this.current + this.pageRange
                return (end < this.totalPages) ? end : this.totalPages
            },
            totalPages() {
                return Math.ceil(this.total / this.itemsPerPage)
            },
            nextPage(){
                return this.current + 1
            },
            prevPage() {
                return this.current - 1
            },
        },

        methods: {
            hasFirst() {
                return this.rangeStart !== 1
            },
            hasLast() {
                return this.rangeEnd < this.totalPages
            },
            hasPrev() {
                return this.current > 1
            },
            hasNext() {
                return this.current < this.totalPages
            },
            change(page) {
                this.$emit('change', page);
            }
        }
    }
</script>

<style scoped>

</style>
