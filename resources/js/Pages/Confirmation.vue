<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: BaseLayout })

const props = defineProps({
    appointment: Object
})

const cancel = () => {
    if (!window.confirm('Are you sure')) {
        return
    }

    router.delete(route('appointments.destroy', props.appointment))
}
</script>

<template>
    <div class="space-y-12">
        <div>
            <h2 class="text-xl font-medium">{{ appointment.cancelled ? 'Cancelled' : `Thanks, you're booked in!` }}</h2>
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

        <div v-if="!appointment.cancelled">
            <button v-on:click="cancel" class="text-blue-500">Cancel appointment</button>
        </div>
    </div>
</template>
