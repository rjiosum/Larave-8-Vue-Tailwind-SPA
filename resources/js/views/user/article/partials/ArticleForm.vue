<template>
    <UserManagementLayout :heading="heading" :user="user">
        <template v-slot:breadcrumbs>
            <BreadCrumbs :heading="getLabel()">
                <template v-slot:links>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.articles'}">Articles</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center"><span class="font-semibold mx-2">{{getLabel()}}</span></li>
                </template>
            </BreadCrumbs>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submitData">
                <div class="form-group">
                    <label for="title">Title <span>*</span></label>
                    <input
                        :class="titleErrors.length > 0 && 'ring-1 ring-red-600'"
                        id="title"
                        type="text"
                        name="title"
                        placeholder="Please enter your first name (e.g John)"
                        v-model.trim="form.title"
                        @input="$v.form.title.$touch()"
                    >
                    <span class="form-error" v-if="titleErrors">{{titleErrors[0]}}</span>
                </div>

                <div class="form-group">
                    <label for="description">Description <span>*</span></label>
                    <ckeditor
                        :editor="editor"
                        v-model.trim="form.description"
                        :config="editorConfig"
                        @input="$v.form.description.$touch()"
                        tag-name="textarea"
                        name="description"
                        id="description"
                    ></ckeditor>
                    <span class="form-error" v-if="descriptionErrors">{{descriptionErrors[0]}}</span>
                </div>

                <div class="form-group">
                    <input id="status" class="rounded-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" type="checkbox" name="status" v-model="form.status"> <label class="inline-block" for="status">Publish?</label>
                </div>

                <AppAlert />

                <div class="mb-6 flex justify-between items-center space-x-4">
                    <FormButton :submitted="submitted" btn-text="Save" />
                    <button class="btn btn-red" @click="back">Back</button>
                </div>

            </form>
        </template>
    </UserManagementLayout>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import CKEditor from '@ckeditor/ckeditor5-vue2';
    import UserManagementLayout from "@/components/layouts/UserManagementLayout";
    import { validationMixin } from 'vuelidate';
    import { required, maxLength } from 'vuelidate/lib/validators';
    import goBack from "@/mixins/goBack";
    import { mapGetters } from 'vuex';
    import { Factory } from '@/repositories/factory';
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";

    const Article = Factory.get('article');

    export default {
        name: "ArticleCreateEditForm",
        mixins: [validationMixin, goBack],
        props: {
            id: {
                type: String,
                required: true
            },
            edit: {
                type: Boolean,
                default: false
            },
            heading: {
                type: String,
                default: 'Write / Share an article!'
            }
        },
        components: {
            ckeditor: CKEditor.component,
            UserManagementLayout,
            BreadCrumbs,
            FormButton,
            AppAlert
        },
        validations: {
            form: {
                title: {required, maxLength: maxLength(150)},
                description: {required}
            }
        },

        data() {
            return {
                form: {
                    title: '',
                    description: '',
                    status: false
                },
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
                },
                submitted: false,
            }
        },

        created() {
            if (this.edit) {
                this.fetch();
            }
        },

        methods: {
            getLabel(){
                return this.edit ? 'Update Article' : 'Create Article';
            },

            async fetch() {
                this.form.check = true;
                this.form.title = 'Loading....';
                this.form.description = 'Loading...';

                await Article.update(this.id, this.form)
                    .then((response) => {
                        const {data: {data: {attributes: {title, description, status}}}} = response;
                        this.form = {title, description, status};
                    }).catch(error => {});
            },

            async submitData() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                try {
                    this.submitted = true;
                    let response;

                    if (this.edit) {
                        response = await Article.update(this.id, this.form);
                    } else {
                        response = await Article.create(this.form);
                    }
                    const {data} = response;

                    const type = (data.status) ? "success" : "error";

                    await this.$store.dispatch('alert/setAlert', {
                        show: true,
                        msg: data.message,
                        type: type
                    });

                    if (!this.edit) {
                        Object.keys(this.form).forEach((key) => {
                            this.form[key] = '';
                        });
                        this.$v.$reset();
                    }
                    this.submitted = false;
                } catch (error) {
                    this.submitted = false;
                }
            },


        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
            }),
            titleErrors() {
                const errors = [];
                if (!this.$v.form.title.$dirty) return errors;
                !this.$v.form.title.required && errors.push('Title is required');
                !this.$v.form.title.maxLength && errors.push('Title cannot be more than 150 characters');
                this.errors.title && errors.push(this.errors.title[0]);
                return errors;
            },
            descriptionErrors() {
                const errors = [];
                if (!this.$v.form.description.$dirty) return errors;
                !this.$v.form.description.required && errors.push('Description is required');
                this.errors.description && errors.push(this.errors.description[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>
