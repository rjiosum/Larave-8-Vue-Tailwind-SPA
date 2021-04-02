<template>
    <CurvedLayout color="bg-green-600">
        <template v-slot:left></template>
        <template v-slot:right>
            <AppAlert class="move-in-right"/>

            <template v-if="!alert">
                <div class="p-4 bg-white shadow-sm move-in-right">
                    <div class="mb-2 text-gray-500 text-center font-bold text-2xl tracking-wide">Resend Link?</div>
                    <div class="mb-6 text-gray-500 text-center font-semibold">Please provide your email address below, and click resend link button</div>
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
                            <FormButton btn-color="btn-indigo" btn-text="Resend Link" :submitted="submitted" />
                        </div>
                    </form>
                </div>
            </template>
        </template>
    </CurvedLayout>
</template>

<script>
    import {validationMixin} from 'vuelidate';
    import {required, email} from 'vuelidate/lib/validators';
    import {mapGetters} from "vuex";
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import {Factory} from '@/repositories/factory';
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";
    const Auth = Factory.get('auth');

    export default {
        name: "AppVerifyEmailResend",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email},
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
                alert: false
            }
        },
        created () {
            if (this.$route.query.email) {
                this.form.email = this.$route.query.email;
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
                    const {data} = await Auth.verifyEmailResend(this.form);
                    this.alert = data.status;
                    this.form.email = '';

                    const type =  (data.status) ? "success" : "error";
                    await this.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });

                    this.submitted = false;
                } catch (error) {
                    this.submitted = false;
                }
            },

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
