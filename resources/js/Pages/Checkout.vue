<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'

defineOptions({ layout: BaseLayout })

const props = defineProps({
    employee: Object,
    service: Object,
})
</script>

<template>
    <form class="space-y-10">
        <div>
            <h2 class="text-xl font-medium">Here's what you're booking</h2>
            <div class="mt-6 flex space-x-3 bg-slate-100 rounded-lg p-4">
                <img :src="employee.profile_photo_url" class="rounded-lg size-14">
                <div class="w-full flex justify-between">
                    <div>
                        <div class="font-semibold">{{ service.title }} ({{ service.duration }} minutes)</div>
                        <div>{{ employee.name }}</div>
                    </div>
                    <div>
                        {{ service.price }}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium">1. When for</h2>
            <div class="mt-6">
                Picker
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium">2. Choose a slot</h2>
            <div class="mt-6">
                Slots
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium">2. Your details and book</h2>
            <div class="mt-6">
                Form
            </div>
        </div>
    </form>
</template>
