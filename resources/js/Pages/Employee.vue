<script setup>
import { Link } from '@inertiajs/vue3'
import BaseLayout from '@/Layouts/BaseLayout.vue'
import Service from '@/Components/Service.vue'

defineOptions({ layout: BaseLayout })

defineProps({
    employee: Object,
    services: Array
})
</script>

<template>
    <div>
        <Link :href="route('home')" class="text-xs text-blue-500">&larr; Go back</Link>
        <h2 class="text-xl font-medium mt-3">Now choose a service from {{ employee.name }}</h2>

        <div class="grid grid-col-2 md:grid-cols-5 gap-8 mt-6">
            <Service v-for="service in services" :key="service.id" :service="service" :href="route('checkout', [service, employee])" />
        </div>
    </div>
</template>
