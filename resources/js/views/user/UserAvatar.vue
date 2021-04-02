<template>
    <UserManagementLayout :user="user" :heading="heading">
        <template v-slot:breadcrumbs>
            <BreadCrumbs heading="Avatar">
                <template v-slot:links>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center"><span class="font-semibold mx-2">Avatar</span></li>
                </template>
            </BreadCrumbs>
        </template>
        <template v-slot:content>
            <form @submit.prevent="update">
                <div class="form-group">
                    <label for="avatar">Avatar <span>*</span></label>
                    <input
                        :class="avatarErrors.length > 0 ? 'ring-1 ring-red-600' : 'ring-1 ring-indigo-500'"
                        class="px-3 py-2 w-full rounded-sm focus:outline-none"
                        id="avatar"
                        type="file"
                        name="avatar"
                        ref="fileupload"
                        @change="newAvatar"
                        @input="$v.avatar.$touch()"
                    >
                    <span class="form-error" v-if="avatarErrors">{{avatarErrors[0]}}</span>
                </div>
                <div v-if="url" class="mb-6 flex justify-start items-end">
                    <div class="w-20 h-20 overflow-hidden"><img class="w-full h-full object-cover" :src="url" alt="avatar" /></div>
                    <button class="px-3 text-red-600 text-2xl" @click="removeImage"><i class="fa fa-trash-alt"></i></button>
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
    import { required } from 'vuelidate/lib/validators';
    import { mapGetters } from 'vuex';
    import UserManagementLayout from "@/components/layouts/UserManagementLayout";
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";
    import { Factory } from '@/repositories/factory';
    const User = Factory.get('user');


    export default {
        name: "UserAvatar",
        mixins: [validationMixin],
        validations: {
            avatar: {required}
        },
        components: {
            AppAlert,
            FormButton,
            BreadCrumbs,
            UserManagementLayout
        },
        data() {
            return {
                heading: 'Update your avatar',
                avatar: null,
                url: null,
                submitted: false,
            }
        },
        methods: {
            newAvatar(event) {
                let files = event.target.files || event.dataTransfer.files;
                if (!files.length) return;
                this.avatar = files[0];
                this.url = URL.createObjectURL(this.avatar);
            },
            removeImage() {
                this.url = null;
                this.avatar = null;
                this.$v.$reset();
                this.$refs.fileupload.value=null;
            },
            async update() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;

                let formData = new FormData();
                formData.append('avatar', this.avatar);

                try {
                    let type = '';
                    const {data} = await User.updateAvatar(formData);

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
                    this.removeImage();

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

            avatarErrors() {
                const errors = [];
                if (!this.$v.avatar.$dirty) return errors;
                !this.$v.avatar.required && errors.push('Image is required');
                this.errors.avatar && errors.push(this.errors.avatar[0]);
                return errors;
            },

        }
    }
</script>

<style scoped>

</style>
