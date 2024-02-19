<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    service: Object
})
</script>

<template>
    <Link class="py-8 px-4 border border-slate-200 rounded-lg shadow-sm flex flex-col items-center justify-center text-center hover:bg-gray-50/75">
        <div class="text-sm font-medium mt-3 text-slate-600">
            {{ service.title }}
        </div>
        <div class="text-sm font-medium mt-1 text-slate-400">
            {{ service.duration }} minutes
        </div>
        <div class="text-xs font-medium mt-3 text-slate-600 bg-slate-200 rounded-lg py-0.5 px-1.5">
            {{ service.price }}
        </div>
    </Link>
</template>
