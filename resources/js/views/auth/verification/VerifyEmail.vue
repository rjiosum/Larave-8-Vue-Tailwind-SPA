<template>
    <CurvedLayout color="bg-indigo-600">
        <template v-slot:left>
        </template>
        <template v-slot:right>

            <AppAlert class="move-in-right" />

            <template v-if="status">
                <div class="text-center">
                    <router-link class="px-4 py-2 inline-block bg-indigo-500 text-white hover:bg-indigo-700" :to="{ name: 'auth.login'}">Proceed with Login</router-link>
                </div>
            </template>
            <template v-else-if="status">
                <div class="text-center">
                    <router-link class="px-4 py-2 inline-block bg-green-500 text-white hover:bg-green-700" :to="{ name: 'verification.resend'}">Resend Verification Link</router-link>
                </div>
            </template>
        </template>
    </CurvedLayout>

</template>

<script>
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import { Factory } from '@/repositories/factory';
    import AppAlert from "@/components/core/AppAlert";

    const Auth = Factory.get('auth');

    const queryString = (params) => Object.keys(params).map(key => key + '=' + params[key]).join('&');

    export default {
        name: "VerifyEmail",
        components: {
            CurvedLayout,
            AppAlert
        },
        data() {
            return {
                status: false,
            }
        },

        async beforeRouteEnter(to, from, next) {
            try {
                const {data} = await Auth.verifyEmail(to.params.id, to.params.hash, queryString(to.query));

                const type = (data.status) ? "success" : "error";

                next(vm => {
                    vm.status = data.status;
                    vm.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });
                });

            } catch (e) {
                next(vm => {
                    vm.status = false;
                    vm.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: e.response.data.message,
                        type: type
                    });
                })
            }
        },

    }
</script>

<style scoped>

</style>
