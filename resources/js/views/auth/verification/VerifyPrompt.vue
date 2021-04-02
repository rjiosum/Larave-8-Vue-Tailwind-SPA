<template>
    <div>
        <div class="text-center">
            <div class="font-bold text-xl text-red-600">Please, verify your email address.</div>
        </div>

        <div class="my-4 p-4 bg-gray-100">
            <div>Before proceeding, please check your email for a verification link. If you did not receive the email, click <router-link class="font-semibold text-indigo-600" :to="url">here</router-link> to request another.</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "VerifyPrompt",

        props: {
            email:{
                type: String,
                default: ''
            }
        },

        computed: {
            url(){
                return `/email/resend?email=${ (this.email == null) ? '' : encodeURIComponent(this.email) }`
            }
        }
    }
</script>

<style scoped>

</style>
