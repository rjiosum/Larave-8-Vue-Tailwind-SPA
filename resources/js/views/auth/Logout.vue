<template>
    <div></div>
</template>

<script>
    export default {
        name: "Logout",
        created() {
            EventBus.$emit('kick')
        }
    }
</script>


