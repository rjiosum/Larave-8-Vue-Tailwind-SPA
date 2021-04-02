<template>
    <div>
        <AppNotify/>
        <AppHeader />
        <div class="h-16"></div>
        <router-view :key="$route.fullPath"></router-view>
        <AppToTop />
    </div>
</template>

<script>

    import AppHeader from "./components/core/AppHeader";
    import AppToTop from "./components/core/AppToTop";
    import AppNotify from "./components/core/AppNotify";

    export default {
        name: "App",
        components: {
            AppHeader,
            AppToTop,
            AppNotify
        }
    }
</script>
