<template>
    <CurvedLayout color="bg-green-500">
        <template v-slot:left>
            <h1 class="move-in-left font-bold text-2xl mb-4">Why join with us ?</h1>
            <div v-for="(j, i) in join" :key="`join-${i}`" class="mb-2 move-in-right">
                <i class="fa fa-check"></i> {{j.text}}
            </div>
            <hr class="my-4 border-green-300">
            <div class="mb-6 move-in-left text-center">Already have account?</div>
            <div class="move-in-bottom text-center">
                <router-link class="btn btn-green" :to="{ name: 'auth.login'}">Login</router-link>
            </div>

        </template>

        <template v-slot:right>

            <template v-if="verifyEmail">
                <VerifyPrompt :email="form.email"></VerifyPrompt>
            </template>

            <template v-else>
                <div class="p-4 bg-white shadow-sm move-in-right">
                    <div class="mb-2 text-gray-500 text-center font-bold text-2xl tracking-wide">Welcome</div>
                    <div class="mb-6 text-gray-500 text-center font-semibold">First, let's get you signed up.</div>
                    <form @submit.prevent="signUp">
                        <div class="grid grid-cols-1 gap-x-2 lg:grid-cols-2">
                            <div class="form-group">
                                <label for="first-name">First Name <span>*</span></label>
                                <input
                                    :class="firstNameErrors.length > 0 && 'ring-1 ring-red-600'"
                                    id="first-name"
                                    type="text"
                                    name="first_name"
                                    placeholder="Please enter your first name (e.g John)"
                                    v-model.trim="form.first_name"
                                    @input="$v.form.first_name.$touch()"
                                    @blur="$v.form.first_name.$touch()"
                                >
                                <span class="form-error" v-if="firstNameErrors">{{firstNameErrors[0]}}</span>
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name <span>*</span></label>
                                <input
                                    :class="lastNameErrors.length > 0 && 'ring-1 ring-red-600'"
                                    id="last-name"
                                    type="text"
                                    name="last_name"
                                    placeholder="Please enter your last name (e.g Doe)"
                                    v-model.trim="form.last_name"
                                    @input="$v.form.last_name.$touch()"
                                    @blur="$v.form.last_name.$touch()"
                                >
                                <span class="form-error" v-if="lastNameErrors">{{lastNameErrors[0]}}</span>
                            </div>
                        </div>

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

                        <div class="text-center">
                            <FormButton btn-color="btn-indigo" btn-text="Sign Up" :submitted="submitted" />
                        </div>
                        <div class="mt-8 text-sm text-center text-gray-500">
                            Already Have account? <router-link class="text-indigo-500 hover:text-indigo-600" :to="{ name: 'auth.login' }">Sign In</router-link>
                        </div>

                    </form>
                </div>
            </template>
        </template>
    </CurvedLayout>

</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email, sameAs } from 'vuelidate/lib/validators';
    import { mapGetters } from 'vuex';
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import FormButton from "@/components/core/FormButton";
    import VerifyPrompt from "./verification/VerifyPrompt";


    export default {
        name: "AppSignUp",

        mixins: [validationMixin],
        validations: {
            form: {
                first_name: {required, maxLength: maxLength(100)},
                last_name: {required, maxLength: maxLength(100)},
                email: {required, email},
                password: {required, minLength: minLength(8)},
                password_confirmation: {required, sameAsPassword: sameAs('password')}
            }
        },
        components: {
            FormButton,
            CurvedLayout,
            VerifyPrompt
        },
        data() {
            return {
                form: {
                    avatar: true,
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                submitted: false,
                showPassword: false,
                showConfirmPassword: false,
                verifyEmail: false
            }
        },
        methods: {
            async signUp() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const response = await this.$store.dispatch('auth/register', this.form);
                    if (response.data.verify) {
                        this.verifyEmail = true;

                    } else {
                        await this.$store.dispatch('notify/setNotice', {
                            show: true,
                            msg: "Registered successfully!!",
                            color: "bg-indigo-500",
                            icon: "fa-info"
                        });
                        if (this.$route.query.redirect) {
                            await this.$router.replace(this.$route.query.redirect);
                            return;
                        }
                        await this.$router.replace({
                            name: 'user.dashboard'
                        });
                    }
                } catch (error) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                join: 'statics/joinUs'
            }),
            firstNameErrors() {
                const errors = [];
                if (!this.$v.form.first_name.$dirty) return errors;
                !this.$v.form.first_name.required && errors.push('First Name is required');
                !this.$v.form.first_name.maxLength && errors.push('First Name cannot be more than 100 characters');
                this.errors.first_name && errors.push(this.errors.first_name[0]);
                return errors;
            },
            lastNameErrors() {
                const errors = [];
                if (!this.$v.form.last_name.$dirty) return errors;
                !this.$v.form.last_name.required && errors.push('Last Name is required');
                !this.$v.form.last_name.maxLength && errors.push('Last Name cannot be more than 100 characters');
                this.errors.last_name && errors.push(this.errors.last_name[0]);
                return errors;
            },
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
