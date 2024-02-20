<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'

defineOptions({ layout: BaseLayout })

defineProps({
    appointment: Object
})
</script>

<template>
    <div class="space-y-12">
        <div>
            <h2 class="text-xl font-medium">Thanks, you're booked in!</h2>
            <div class="mt-6 flex space-x-3 bg-slate-100 rounded-lg p-4">
                <img :src="appointment.employee.profile_photo_url" class="rounded-lg size-14">

                <div class="w-full flex justify-between">
                    <div>
                        <div class="font-semibold">{{ appointment.service.title }} ({{ appointment.service.duration }} minutes)</div>
                        <div>{{ appointment.employee.name }}</div>
                    </div>
                    <div>
                        {{ appointment.service.price }}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium">When</h2>
            <div class="mt-6 bg-slate-100 rounded-lg p-4">
                {{ appointment.date }} {{ appointment.starts_at }} until {{ appointment.ends_at }}
            </div>
        </div>
    </div>
</template>
