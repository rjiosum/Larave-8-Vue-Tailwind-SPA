<template>
    <CurvedLayout color="bg-purple-600">
        <template v-slot:left>
        </template>
        <template v-slot:right>

            <AppAlert class="move-in-right"/>

            <template v-if="!alert">
                <div class="p-4 bg-white shadow-sm move-in-right">
                    <div class="mb-2 text-gray-500 text-center font-bold text-2xl tracking-wide">Recover</div>
                    <div class="mb-6 text-gray-500 text-center font-semibold">It happens. Enter your email address and we'll send you a password reset link.</div>
                    <form @submit.prevent="send">
                        <div class="form-group">
                            <label for="email">Email <span>*</span></label>
                            <input
                                :class="emailErrors.length > 0 && 'ring-1 ring-red-600'"
                                id="email"
                                type="email"
                                name="email"
                                placeholder="Please enter your email address (e.g john@gmail.com)"
                                v-model.trim="form.email"
                                @input="$v.form.email.$touch()"
                                @blur="$v.form.email.$touch()"
                            >
                            <span class="form-error" v-if="emailErrors">{{emailErrors[0]}}</span>
                        </div>
                        <div class="text-center">
                            <FormButton btn-color="btn-indigo" btn-text="Continue" :submitted="submitted" />
                        </div>

                        <div class="mt-8 text-sm text-center text-gray-500">Remember it? <router-link class="text-indigo-500 hover:text-indigo-600" :to="{ name: 'auth.login' }">Sign In</router-link></div>
                    </form>
                </div>
            </template>

        </template>
    </CurvedLayout>

</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email } from 'vuelidate/lib/validators'
    import { mapGetters } from "vuex";
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import { Factory } from '@/repositories/factory';
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";

    const Auth = Factory.get('auth');

    export default {
        name: "PasswordResetEmail",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email}
            }
        },
        components: {
            CurvedLayout,
            FormButton,
            AppAlert
        },
        data() {
            return {
                form: {
                    email: null,
                },
                submitted: false,
                alert: false,
            }
        },
        methods: {
            async send() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const {data} = await Auth.sendResetPasswordLinkEmail(this.form);
                    this.alert = data.status;
                    this.form.email = '';

                    const type =  (data.status) ? "success" : "error";
                    await this.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });

                    this.submitted = false;
                } catch (e) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({errors: "errors/errors"}),

            emailErrors() {
                const errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.email && errors.push('Must be valid e-mail');
                !this.$v.form.email.required && errors.push('E-mail is required');
                this.errors.email && errors.push(this.errors.email[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>
