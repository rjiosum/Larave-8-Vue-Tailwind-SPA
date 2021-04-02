<template>
    <UserManagementLayout :user="user" :heading="heading">
        <template v-slot:breadcrumbs>
            <BreadCrumbs heading="Password">
                <template v-slot:links>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center"><span class="font-semibold mx-2">Password</span></li>
                </template>
            </BreadCrumbs>
        </template>
        <template v-slot:content>
            <form @submit.prevent="update">
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
                <AppAlert/>
                <div class="mb-6 text-center">
                    <FormButton btn-color="btn-indigo" btn-text="Update" :submitted="submitted" />
                </div>
            </form>
        </template>
    </UserManagementLayout>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, minLength, sameAs } from 'vuelidate/lib/validators';
    import { mapGetters } from 'vuex';
    import UserManagementLayout from "@/components/layouts/UserManagementLayout";
    import { Factory } from '@/repositories/factory';
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";

    const User = Factory.get('user');

    export default {
        name: "UserPassword",
        mixins: [validationMixin],
        validations: {
            form: {
                password: {required, minLength: minLength(8)},
                password_confirmation: {required, sameAsPassword: sameAs('password')}
            }
        },
        components: {
            UserManagementLayout,
            BreadCrumbs,
            FormButton,
            AppAlert
        },
        data() {
            return {
                heading: 'Update your password',
                form: {
                    password: '',
                    password_confirmation: ''
                },
                submitted: false,
            }
        },
        methods: {
            async update() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const {data} = await User.updatePassword(this.form);

                    const type =  (data.status) ? "success" : "error";
                    await this.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });

                    Object.keys(this.form).forEach((key) => {
                        this.form[key] = '';
                    });
                    this.$v.$reset();
                    this.submitted = false;
                } catch (error) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
            }),
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

<style scoped>

</style>
