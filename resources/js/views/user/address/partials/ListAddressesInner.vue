<template>
    <div class="flex flex-col p-4 bg-white text-gray-500 move-in-right">

        <div class="mb-8">
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.address_1}}</div>
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.address_2}}</div>
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.town}}</div>
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.county}}</div>
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.postcode}}</div>
            <div class="mb-1 font-medium tracking-wide text-sm italic">{{address.country.name}}</div>
        </div>
        <div class="mt-auto">
            <div class="flex justify-between items-center">
                <router-link class="px-3 py-2 text-xs font-semibold tracking-widest inline-flex justify-center items-center rounded-sm shadow-md text-white bg-green-500 hover:bg-green-600" :to="{name: 'user.edit.address', params:{id: address.uuid}}"><i class="fas fa-save"></i><span class="inline-block ml-1">Edit</span></router-link>

                <button :dusk="address.uuid" class="px-3 py-2 text-xs font-semibold tracking-widest inline-flex justify-center items-center rounded-sm shadow-md text-white bg-red-500 hover:bg-red-600" @click="deleteAddress(address)"><i class="fas fa-trash-alt"></i><span class="inline-block ml-1">Delete</span></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ListAddressesInner",
        props:{
            address: {
                type: Object,
                required: true
            }
        },
        methods: {
            deleteAddress(address) {
                this.$emit('delete', address);
            },
        },
    }
</script>

<style scoped>

</style>
