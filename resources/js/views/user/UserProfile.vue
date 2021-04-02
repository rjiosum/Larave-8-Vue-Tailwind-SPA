<template>
    <UserManagementLayout :user="user" :heading="heading">
        <template v-slot:breadcrumbs>
            <BreadCrumbs heading="Profile">
                <template v-slot:links>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center"><span class="font-semibold mx-2">Profile</span></li>
                </template>
            </BreadCrumbs>
        </template>
        <template v-slot:content>
                <form @submit.prevent="update">
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
                    <AppAlert/>
                    <div class="mb-6 text-center">
                        <FormButton :submitted="submitted" btn-text="Update" />
                    </div>
                </form>
        </template>
    </UserManagementLayout>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength } from 'vuelidate/lib/validators';
    import { mapGetters } from 'vuex';
    import { Factory } from '@/repositories/factory';
    import UserManagementLayout from "@/components/layouts/UserManagementLayout";
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";

    const User = Factory.get('user');

    export default {
        name: "UserProfile",
        mixins: [validationMixin],
        validations: {
            form: {
                first_name: {required, maxLength: maxLength(100)},
                last_name: {required, maxLength: maxLength(100)},
            }
        },
        components: {
            AppAlert,
            FormButton,
            BreadCrumbs,
            UserManagementLayout
        },
        data() {
            return {
                heading: 'Update your account details',
                form: {
                    first_name: '',
                    last_name: ''
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
                    let type = '';
                    const {data} = await User.updateProfile(this.form);

                    if (data.status) {
                        await this.$store.dispatch('auth/getUser');
                        type = "success";
                    } else {
                        type = "error";
                    }

                    await this.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });

                    this.submitted = false;

                } catch (error) {
                    this.submitted = false;
                }
            }
        },
        created() {
            Object.keys(this.form).forEach((key) => {
                this.form[key] = this.user[key]
            });
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
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
            }
        }
    }
</script>

<style scoped>

</style>
