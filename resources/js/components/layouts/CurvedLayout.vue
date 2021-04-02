<template>
    <section class="flex justify-between items-center">
        <div class="hidden sm:block h-cal-64 w-1/2 v-curve" :class="color">
            <div class="text-white h-full flex justify-center items-center">
                <div class="sm:w-11/12 lg:w-2/3">
                    <slot name="left"></slot>
                </div>
            </div>
        </div>
        <div class="h-cal-64 w-full sm:w-1/2">
            <div class="h-full flex justify-center items-center">
                <div class="w-full px-4 sm:p-0 sm:w-11/12 lg:w-2/3">
                    <slot name="right"></slot>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "CurvedLayout",
        props: {
            color: {
                type: String,
                default: 'bg-indigo-400'
            }
        }
    }
</script>

<style scoped>

</style>
