<template>
    <div>
        <div v-if="dialog" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-auto my-6 mx-auto max-w-sm" style="min-width: 250px">
                <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                    <div class="px-4 py-3 bg-red-500 text-white text-sm font-bold tracking-wide rounded-t">{{title}}</div>
                    <div class="p-4 bg-white">{{message}}</div>
                    <hr>
                    <div class="p-4 flex justify-between items-center space-x-4 rounded-b">
                        <button @click="cancel" class="px-3 py-2 inline-flex items-center space-x-2 rounded-sm bg-red-500 text-white hover:bg-red-600 focus:outline-none focus:ring-1 focus:ring-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Cancel</button>
                        <button @click="agree" class="px-3 py-2 inline-flex items-center space-x-2 rounded-sm bg-green-500 text-white hover:bg-green-600 focus:outline-none focus:ring-1 focus:ring-green-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="dialog" class="opacity-25 fixed inset-0 z-40 bg-black"></div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                dialog: false,
                resolve: null,
                reject: null,
                message: '',
                title: ''
            }
        },
        methods: {
            open(title, message) {
                this.dialog = true;
                this.title = title;
                this.message = message;

                return new Promise((resolve, reject) => {
                    this.resolve = resolve;
                    this.reject = reject;
                })
            },
            agree() {
                this.resolve(true);
                this.dialog = false;
            },
            cancel() {
                this.resolve(false);
                this.dialog = false;
            }
        }
    }
</script>
