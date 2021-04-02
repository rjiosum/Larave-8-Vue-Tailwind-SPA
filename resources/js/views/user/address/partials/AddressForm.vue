<template>
    <UserManagementLayout :heading="heading" :user="user">
        <template v-slot:breadcrumbs>
            <BreadCrumbs :heading="getLabel()">
                <template v-slot:links>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.addresses'}">Addresses</router-link><i class="fa fa-angle-right"></i></li>
                    <li class="inline-flex items-center"><span class="font-semibold mx-2">{{getLabel()}}</span></li>
                </template>
            </BreadCrumbs>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submitData">
                <div class="form-group">
                    <label for="postcode">Postcode <span>*</span></label>
                    <input
                        :class="postcodeErrors.length > 0 && 'ring-1 ring-red-600'"
                        id="postcode"
                        type="text"
                        name="postcode"
                        placeholder="Please enter your postcode"
                        v-model.trim="form.postcode"
                        @input="$v.form.postcode.$touch()"
                    >
                    <span class="form-error" v-if="postcodeErrors">{{postcodeErrors[0]}}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                    <div class="form-group">
                        <label for="address-1">Address Line 1 <span>*</span></label>
                        <input
                            :class="addressOneErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="address-1"
                            type="text"
                            name="address_1"
                            placeholder="Please enter address line 1 (e.g 23, Amity Road)"
                            v-model.trim="form.address_1"
                            @input="$v.form.address_1.$touch()"
                        >
                        <span class="form-error" v-if="addressOneErrors">{{addressOneErrors[0]}}</span>
                    </div>

                    <div class="form-group">
                        <label for="address-2">Address Line 2 <strong>(Optional)</strong></label>
                        <input
                            :class="addressTwoErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="address-2"
                            type="text"
                            name="address_2"
                            placeholder="Please enter address line 2"
                            v-model.trim="form.address_2"
                            @input="$v.form.address_2.$touch()"
                        >
                        <span class="form-error" v-if="addressTwoErrors">{{addressTwoErrors[0]}}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                    <div class="form-group">
                        <label for="town">Town <span>*</span></label>
                        <input
                            :class="townErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="town"
                            type="text"
                            name="town"
                            placeholder="Please enter your town/city"
                            v-model.trim="form.town"
                            @input="$v.form.town.$touch()"
                        >
                        <span class="form-error" v-if="townErrors">{{townErrors[0]}}</span>
                    </div>

                    <div class="form-group">
                        <label for="county">County <span>*</span></label>
                        <input
                            :class="countyErrors.length > 0 && 'ring-1 ring-red-600'"
                            id="county"
                            type="text"
                            name="county"
                            placeholder="Please enter your county"
                            v-model.trim="form.county"
                            @input="$v.form.county.$touch()"
                        >
                        <span class="form-error" v-if="countyErrors">{{countyErrors[0]}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="country-id">Country <span>*</span></label>
                    <select name="country_id" id="country-id" v-model="form.country_id">
                        <option value="">Please select your country</option>
                        <template v-for="country in countries">
                            <option :key="country.id" :value="country.id">{{country.name}}</option>
                        </template>
                    </select>
                    <span class="form-error" v-if="countryIdErrors">{{countryIdErrors[0]}}</span>
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
    import UserManagementLayout from "@/components/layouts/UserManagementLayout";
    import {validationMixin} from 'vuelidate';
    import {maxLength, required} from 'vuelidate/lib/validators';
    import goBack from "@/mixins/goBack";
    import {mapGetters} from 'vuex';
    import {Factory} from '@/repositories/factory';
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import FormButton from "@/components/core/FormButton";
    import AppAlert from "@/components/core/AppAlert";

    const Address = Factory.get('address');
    const Country = Factory.get('country');

    export default {
        name: "AddressCreateEditForm",
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
                default: 'Add Your Address'
            }
        },
        components: {
            UserManagementLayout,
            BreadCrumbs,
            FormButton,
            AppAlert
        },
        validations: {
            form: {
                address_1: {required, maxLength: maxLength(300)},
                address_2: {maxLength: maxLength(300)},
                town: {required, maxLength: maxLength(100)},
                county: {required, maxLength: maxLength(100)},
                postcode: {required, maxLength: maxLength(20)},
                country_id: {required},
            }
        },

        data() {
            return {
                form: {
                    address_1: '',
                    address_2: '',
                    town: '',
                    county: '',
                    postcode: '',
                    country_id: ''
                },
                countries: [],
                submitted: false,
            }
        },

        created() {
            if (this.edit) {
                this.fetch();
            }
            this.fetchCountries();
        },

        methods: {
            getLabel(){
                return this.edit ? 'Update Address' : 'Add Address';
            },

            async fetchCountries() {
                await Country.index()
                    .then((response) => {
                        const {data: {data}} = response;
                        this.countries = data.map(item => {
                            const {data: {attributes: {id, name}}} = item;
                            return {id, name};
                        });
                    }).catch(error => {});
            },

            async fetch() {
                this.form.check = true;
                this.form.address_1 = 'Loading....';
                this.form.address_2 = 'Loading...';
                this.form.town = 'Loading...';
                this.form.county = 'Loading...';
                this.form.postcode = 'Loading...';
                this.form.country_id = 'Loading...';

                await Address.update(this.id, this.form)
                    .then((response) => {
                        const {data: {data: {attributes: {address_1, address_2, town, county, postcode, country_id}}}} = response;
                        this.form = {address_1, address_2, town, county, postcode, country_id};
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
                        response = await Address.update(this.id, this.form);
                    } else {
                        response = await Address.create(this.form);
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
            addressOneErrors() {
                const errors = [];
                if (!this.$v.form.address_1.$dirty) return errors;
                !this.$v.form.address_1.required && errors.push('Address line 1 is required');
                !this.$v.form.address_1.maxLength && errors.push('Address line 1 cannot be more than 300 characters');
                this.errors.address_1 && errors.push(this.errors.address_1[0]);
                return errors;
            },
            addressTwoErrors() {
                const errors = [];
                if (!this.$v.form.address_2.$dirty) return errors;
                !this.$v.form.address_2.maxLength && errors.push('Address line 2 cannot be more than 300 characters');
                this.errors.address_2 && errors.push(this.errors.address_2[0]);
                return errors;
            },
            townErrors() {
                const errors = [];
                if (!this.$v.form.town.$dirty) return errors;
                !this.$v.form.town.required && errors.push('Town is required');
                !this.$v.form.town.maxLength && errors.push('Town cannot be more than 100 characters');
                this.errors.town && errors.push(this.errors.town[0]);
                return errors;
            },
            countyErrors() {
                const errors = [];
                if (!this.$v.form.county.$dirty) return errors;
                !this.$v.form.county.required && errors.push('County is required');
                !this.$v.form.county.maxLength && errors.push('County cannot be more than 100 characters');
                this.errors.county && errors.push(this.errors.county[0]);
                return errors;
            },
            postcodeErrors() {
                const errors = [];
                if (!this.$v.form.postcode.$dirty) return errors;
                !this.$v.form.postcode.required && errors.push('Postcode is required');
                !this.$v.form.postcode.maxLength && errors.push('Postcode cannot be more than 100 characters');
                this.errors.postcode && errors.push(this.errors.postcode[0]);
                return errors;
            },
            countryIdErrors() {
                const errors = [];
                if (!this.$v.form.country_id.$dirty) return errors;
                !this.$v.form.country_id.required && errors.push('Country is required');
                this.errors.country_id && errors.push(this.errors.country_id[0]);
                return errors;
            },
        }
    }
</script>

<style scoped>

</style>

