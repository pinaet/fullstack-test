<template>
    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-4 text-3xl font-bold">404 - Page Not Found</h1>
        <p class="text-gray-600">The requested page or province does not exist.</p>
        <Link :href="route('home-page')" class="text-blue-500 underline">Go back to the homepage</Link>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },

    data() {
        return {
            properties: []
        };
    },
};
</script>
