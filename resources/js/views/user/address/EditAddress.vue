<template>
    <AddressForm :id="id" :heading="heading" :edit="true" />
</template>

<script>
    import AddressForm from "./partials/AddressForm";

    export default {
        name: "EditAddress",
        props: {
            id: {
                type: String,
                required: true
            }
        },
        components: {
            AddressForm
        },
        data() {
            return {
                heading: 'Update Your Address',
            }
        }
    }
</script>

<style scoped>

</style>
