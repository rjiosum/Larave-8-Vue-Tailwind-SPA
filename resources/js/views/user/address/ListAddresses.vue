<template>
    <div class="mt-10 move-in-left">

        <BreadCrumbs heading="Addresses">
            <template v-slot:links>
                <li class="inline-flex items-center text-gray-500"><router-link class="mx-2" :to="{name: 'user.dashboard'}">Dashboard</router-link><i class="fa fa-angle-right"></i></li>
                <li class="inline-flex items-center"><span class="font-semibold mx-2">Addresses</span></li>
            </template>
        </BreadCrumbs>

        <div class="p-4 mb-6 bg-white flex flex-wrap justify-between items-center space-x-4">
            <template v-if="isReady">
                <div class="mb-2 sm:mb-0 flex-grow"><AppPagination store="address" @next="next" /></div>
            </template>
            <div class="mb-2 sm:mb-0 flex-shrink-0"><router-link class="block w-full btn btn-indigo" :to="{name: 'user.create.address'}"><i class="fas fa-pen"></i> Add New Address</router-link></div>
        </div>

        <Spinner v-if="loading" />

        <template v-if="isReady">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <ListAddressesInner
                    v-for="address in addresses"
                    :key="address.uuid"
                    :address="address"
                    @delete="deleteAddress"
                />
            </div>
        </template>

        <div v-cloak v-if="pagination.total===0" class="text-2xl font-bold text-center">No addresses found!!</div>
        <AppConfirm ref="confirm"></AppConfirm>

    </div>
</template>

<script>
    import AppConfirm from "@/components/core/AppConfirm";
    import AppPagination from "@/components/core/AppPagination";
    import handleNext from "@/mixins/handleNext";
    import { mapGetters } from "vuex";
    import { Factory } from '@/repositories/factory';
    import BreadCrumbs from "@/components/core/BreadCrumbs";
    import Spinner from "@/components/core/Spinner";
    import ListAddressesInner from "./partials/ListAddressesInner";

    const Address = Factory.get('address');

    export default {
        name: "ListAddresses",
        components: {
            Spinner,
            BreadCrumbs,
            AppPagination,
            AppConfirm,
            ListAddressesInner
        },
        mixins: [handleNext],
        data() {
            return {
                defaults: {
                    page: parseInt(this.$route.query.page) || 1,
                    per_page: parseInt(this.$route.query.per_page) || 12
                },
            }
        },
        methods: {
            fetch(params) {
                this.$store.dispatch("address/setAddresses", {params})
            },

            async deleteAddress(address) {
                const confirm = await this.$refs.confirm.open('Delete', 'Are you sure?');
                if (confirm) {
                    try {
                        const {data} = await Address.destroy(address.uuid);

                        if (data.status) {
                            await this.$store.dispatch('notify/setNotice', {
                                show: true,
                                msg: data.message,
                                color: "bg-red-500",
                                icon: "fa-exclamation-triangle"
                            });
                            this.fetch(this.defaults);
                        }

                    } catch (error) {
                        //console.log(error);
                    }
                }
            }
        },

        created() {
            this.fetch(this.defaults);
        },

        computed: {
            isReady(){
                return this.pagination.total > 0 && !this.loading;
            },

            ...mapGetters({
                addresses: 'address/addresses',
                pagination: 'address/pagination',
                filters: 'address/filters',
                loading: 'address/loading',
                isAuthenticated: 'auth/isAuthenticated',
                user: 'auth/user',
            }),
        }
    }
</script>

<style scoped>

</style>
