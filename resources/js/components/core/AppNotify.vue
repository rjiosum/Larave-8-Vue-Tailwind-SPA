<template>
    <div class="fixed top-16 left-0 w-full h-auto flex justify-center z-50">
        <transition name="notify">
            <div v-if="notice.show">
                <div :class="notice.color" class="px-6 py-4 text-white space-x-8 shadow-md rounded-b-md flex justify-center items-center">
                    <span class="flex-shrink-0 inline-block w-10 h-10 p-1 bg-black bg-opacity-25 rounded-full text-xl shadow-inner flex items-center justify-center"><i class="fa" :class="notice.icon"></i></span>
                    <span class="inline-block max-w-xs font-medium tracking-wide">{{notice.msg}}</span>
                    <button class="flex-shrink-0 px-3 py-1 text-2xl bg-black bg-opacity-25 rounded-sm shadow-sm hover:bg-opacity-50 focus:outline-none focus:ring-1 focus:ring-gray-300" @click="close"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </transition>
    </div>

</template>

<script>

    export default {
        name: "AppNotify",
        data() {
            return {
                notice: {
                    show: false,
                    msg: '',
                    color: '',
                    icon: '',
                    timeout: -1
                }
            }
        },
        methods: {
            close() {
                this.notice.show = false;
                this.$store.dispatch('notify/setNotice', {show: false, msg: ''});
            }
        },
        created() {
            this.$store.watch(state => state.notify.notice.show, () => {
                const msg = this.$store.state.notify.notice.msg;
                if (msg !== '') {
                    this.notice = {...this.notice, ...this.$store.state.notify.notice}
                    setTimeout(()=> {
                        this.close()
                    }, this.notice.timeout)
                }
            })
        }
    }
</script>
