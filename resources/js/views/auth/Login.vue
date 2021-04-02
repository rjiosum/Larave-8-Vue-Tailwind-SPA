<template>
    <CurvedLayout>
        <template v-slot:left>
            <h1 class="move-in-left font-bold text-2xl mb-4">Why join with us ?</h1>
            <div v-for="(j, i) in join" :key="`join-${i}`" class="mb-2 move-in-right">
                <i class="fa fa-check"></i> {{j.text}}
            </div>
            <hr class="my-4 border-indigo-300">
            <div class="mb-6 move-in-left text-center">Want to create new account?</div>
            <div class="move-in-bottom text-center">
                <router-link class="btn btn-indigo" :to="{ name: 'auth.register'}">Sign Up</router-link>
            </div>
        </template>
        <template v-slot:right>
            <div class="p-4 bg-white shadow-sm move-in-right">
                <div class="mb-2 text-gray-500 text-center font-bold text-2xl tracking-wide">Hello</div>
                <div class="mb-6 text-gray-500 text-center font-semibold">Great to see you back. Now login and get started.</div>
                <form @submit.prevent="login">
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
                        <input id="remember" class="rounded-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" type="checkbox" name="remember" v-model="form.remember"> <label class="inline-block" for="remember">Stay signed in</label>
                    </div>

                    <template v-if="verifyEmail">
                        <VerifyPrompt :email="form.email"></VerifyPrompt>
                    </template>

                    <div class="text-center">
                        <FormButton btn-color="btn-indigo" btn-text="Login" :submitted="submitted" />
                    </div>

                    <div class="mt-8 text-sm text-center text-gray-500">
                        New? <router-link class="text-indigo-500 hover:text-indigo-600" :to="{ name: 'auth.register' }">Sign Up</router-link> | <router-link class="text-indigo-500 hover:text-indigo-600" :to="{ name: 'password.reset.email' }">Forgot Password?</router-link>
                    </div>
                </form>
            </div>
        </template>
    </CurvedLayout>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email } from 'vuelidate/lib/validators'
    import { mapGetters } from "vuex";
    import CurvedLayout from "@/components/layouts/CurvedLayout";
    import VerifyPrompt from "./verification/VerifyPrompt";
    import FormButton from "@/components/core/FormButton";

    export default {
        name: "Login",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email},
                password: {required}
            }
        },
        components: {
            FormButton,
            CurvedLayout,
            VerifyPrompt
        },
        data() {
            return {
                showPassword: false,
                form: {
                    email: null,
                    password: null,
                    remember: false
                },
                submitted: false,
                verifyEmail: false
            }
        },
        methods: {
            async login() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const response = await this.$store.dispatch('auth/login', this.form);

                    if (response.data.status) {
                        await this.$store.dispatch('notify/setNotice', {
                            show: true,
                            msg: "Successfully logged in.",
                            color: "bg-indigo-500",
                            icon: "fa-info"
                        });
                        if (this.$route.query.redirect) {
                            await this.$router.replace(this.$route.query.redirect);
                            return;
                        }
                        await this.$router.replace({name: 'user.dashboard'});
                    }
                } catch (e) {
                    //console.log(e)
                    this.submitted = false;
                }

            }
        },
        computed: {
            ...mapGetters({
                errors: "errors/errors",
                join: "statics/joinUs"
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
                this.errors.password && errors.push(this.errors.password[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>
