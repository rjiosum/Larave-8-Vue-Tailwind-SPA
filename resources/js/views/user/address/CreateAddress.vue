<template>
    <AddressForm :id="id" />
</template>

<script>
    import AddressForm from "./partials/AddressForm";

    export default {
        name: "CreateAddress",
        components:{
            AddressForm
        },
        data() {
            return {
                id: 'none'
            }
        },
    }
</script>

<style scoped>

</style>
