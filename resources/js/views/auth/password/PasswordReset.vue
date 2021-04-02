<template>
    <CurvedLayout color="bg-pink-600">
        <template v-slot:left>
        </template>
        <template v-slot:right>
            <div class="p-4 bg-white shadow-sm move-in-right">
                <div class="mb-6 text-gray-500 text-center font-semibold">Reset your password.</div>
                <form @submit.prevent="reset">
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
                    <div class="form-group">
                        <label for="password">Password <span>*</span></label>
                        <input
                            :class="passwordErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Please enter your password"
                            v-model.trim="form.password"
                            @input="$v.form.password.$touch()"
                            @blur="$v.form.password.$touch()"
                        >
                        <span class="form-error" v-if="passwordErrors">{{passwordErrors[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label for="password-confirmation">Confirm Password <span>*</span></label>
                        <input
                            :class="confirmPasswordErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="password-confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Please re-enter your password"
                            v-model.trim="form.password_confirmation"
                            @input="$v.form.password_confirmation.$touch()"
                            @blur="$v.form.password_confirmation.$touch()"
                        >
                        <span class="form-error" v-if="confirmPasswordErrors">{{confirmPasswordErrors[0]}}</span>
                    </div>

                    <AppAlert />

                    <div class="text-center">
                        <FormButton btn-color="btn-indigo" btn-text="Reset" :submitted="submitted" />
                    </div>

                </form>
            </div>
        </template>
    </CurvedLayout>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, minLength, email, sameAs } from 'vuelidate/lib/validators';
    import { mapGetters } from 'vuex';
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import { Factory } from '@/repositories/factory';
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";
    const Auth = Factory.get('auth');

    export default {
        name: "PasswordReset",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email},
                password: {required, minLength: minLength(6)},
                password_confirmation: {required, sameAsPassword: sameAs('password')}
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
                    token: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                submitted: false
            }
        },
        created() {
            this.form.email = this.$route.query.email;
            this.form.token = this.$route.params.token;
        },
        methods: {
            async reset() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const {data} = await Auth.resetPassword(this.form);
                    if(data.status){
                        await this.$store.dispatch('notify/setNotice', {
                            show: true,
                            msg: data.message + ". Please enter your new details to login",
                            color: "bg-indigo-500",
                            icon: "fa-info"
                        });
                        await this.$router.replace({
                            name: 'auth.login'
                        });
                    } else {
                        await this.$store.dispatch('alert/setAlert', {
                            show: true,
                            msg: data.message,
                            type: 'error'
                        });
                        this.submitted = false;
                    }
                } catch (e) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors'
            }),
            emailErrors() {
                const errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.email && errors.push('Must be valid e-mail');
                !this.$v.form.email.required && errors.push('E-mail is required');
                this.errors.email && errors.push(this.errors.email[0]);
                return errors;
            },
            passwordErrors() {
                const errors = [];
                if (!this.$v.form.password.$dirty) return errors;
                !this.$v.form.password.required && errors.push('Password is required');
                !this.$v.form.password.minLength && errors.push('Password should be more than 6 characters');
                this.errors.password && errors.push(this.errors.password[0]);
                return errors;
            },
            confirmPasswordErrors() {
                const errors = [];
                if (!this.$v.form.password_confirmation.$dirty) return errors;
                !this.$v.form.password_confirmation.required && errors.push('Please confirm your password');
                !this.$v.form.password_confirmation.sameAsPassword && errors.push('Password and confirm password do not match');
                this.errors.password_confirmation && errors.push(this.errors.password_confirmation[0]);
                return errors;
            },
        }
    }
</script>
