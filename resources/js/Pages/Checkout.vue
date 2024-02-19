<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'
import { ref, onMounted } from 'vue'
import { easepick } from '@easepick/bundle'
import style from '@easepick/bundle/dist/index.css?url'

defineOptions({ layout: BaseLayout })

const props = defineProps({
    employee: Object,
    service: Object,
    availability: Array,
})

const pickerRef = ref(null)
let picker = null

const createPicker = () => {
    picker = new easepick.create({
        element: pickerRef.value,
        readonly: true,
        zIndex: 50,
        css: [
            style
        ]
    })
}

onMounted(() => {
    createPicker()
})
</script>

<template>
    <form class="space-y-10">
        <div>
            <h2 class="text-xl font-medium">Here's what you're booking</h2>
            <div class="mt-6 flex space-x-3 bg-slate-100 rounded-lg p-4">
                <img v-if="employee" :src="employee.profile_photo_url" class="rounded-lg size-14">
                <div v-else class="rounded-lg size-14 bg-slate-200"></div>

                <div class="w-full flex justify-between">
                    <div>
                        <div class="font-semibold">{{ service.title }} ({{ service.duration }} minutes)</div>
                        <div>{{ employee?.name ?? 'Any employee' }}</div>
                    </div>
                    <div>
                        {{ service.price }}
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium">1. When for</h2>
            <input ref="pickerRef" class="mt-6 text-sm bg-slate-100 border-0 rounded-lg px-6 py-4 w-full" placeholder="Choose a date">
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
