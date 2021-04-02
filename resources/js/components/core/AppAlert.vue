<template>
    <div :class="getClass" class="p-4 mb-6 text-white space-x-6 shadow-md rounded-sm flex justify-between items-center" v-if="attributes.show">
        <div class="flex-shrink-0 w-10 h-10 p-1 text-gray-300 bg-black bg-opacity-25 rounded-full text-xl shadow-inner flex items-center justify-center" v-html="getIcon"></div>
        <div class="flex-grow font-medium tracking-wide">{{attributes.msg}}</div>
        <button type="button" class="flex-shrink-0 w-10 h-10 p-1 bg-black bg-opacity-25 rounded-full shadow-sm hover:bg-opacity-50 focus:outline-none focus:ring-1 focus:ring-gray-300 flex items-center justify-center" @click="close"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
</template>

<script>
    export default {
        name: "AppAlert",

        data(){
            return {
                attributes: {
                    show: false,
                    msg: '',
                    type: 'success'
                }
            }
        },
        created() {
            this.$store.watch(state => state.alert.attributes.show, () =>{
                const msg = this.$store.state.alert.attributes.msg;
                if (msg !== '') {
                    this.attributes = {...this.attributes, ...this.$store.state.alert.attributes}
                    setTimeout(()=> {
                        this.$store.dispatch('alert/setAlert', {show: false, msg: ''});
                    }, 2000)
                }
            })
        },
        methods: {
            close() {
                this.attributes.show = false;
                this.$store.dispatch('alert/setAlert', {show: false, msg: ''});
            },
        },
        computed: {
            getIcon(){
                let icon = '';
                switch (this.attributes.type) {
                    case 'info':
                        icon = '<svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                        break;
                    case 'error':
                        icon = '<svg class="w-10 h-10 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
                        break;
                    case 'warning':
                        icon = '<svg class="w-10 h-10 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>'
                        break;
                    default:
                        icon = '<svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>'
                        break;
                }
                return icon;
            },

            getClass(){
                let className = '';
                switch (this.attributes.type) {
                    case 'info':
                        className = 'bg-indigo-500';
                        break;
                    case 'error':
                        className = 'bg-red-500'
                        break;
                    case 'warning':
                        className = 'bg-yellow-500'
                        break;
                    default:
                        className = 'bg-green-500'
                        break;
                }
                return className;
            }
        }
    }
</script>

<style scoped>

</style>
