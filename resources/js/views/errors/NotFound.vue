<template>
    <CurvedLayout color="bg-red-600">
        <template v-slot:left>
            <div class="text-5xl font-bold text-white move-in-bottom">OOPS !!</div>
        </template>

        <template v-slot:right>
            <div class="text-center move-in-right">
                <div class="text-3xl font-bold mb-4">404 Page not found</div>
                <hr class="my-6">
                <h4 class="font-medium">Click the button below to go back to home</h4>
                <div class="mt-4">
                    <router-link class="btn btn-indigo" :to="{name : 'home'}">Home</router-link>
                </div>
            </div>
        </template>
    </CurvedLayout>
</template>

<script>
    import CurvedLayout from "@/components/layouts/CurvedLayout";

    export default {
        name: "NotFound",
        components: {
            CurvedLayout
        }
    }
</script>
