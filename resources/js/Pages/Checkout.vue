<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'
import { ref, onMounted, watch } from 'vue'
import { easepick, LockPlugin, DateTime } from '@easepick/bundle'
import style from '@easepick/bundle/dist/index.css?url'
import customStyle from '../../../resources/css/vendor/easepick.css?url'
import { router, useForm } from '@inertiajs/vue3'
import pluralize from 'pluralize'

defineOptions({ layout: BaseLayout })

const props = defineProps({
    employee: Object,
    service: Object,
    availability: Array,
    date: String,
    calendar: String
})

const form = useForm({
    service_id: props.service.id,
    employee_id: props.employee?.id || null,
    datetime: null,
    name: null,
    email: null
})

const submit = () => {
    form.post(route('appointments.store'), {
        preserveScroll: true
    })
}

watch(() => form.datetime, () => {
    if (form.employee_id) {
        return
    }

    router.get(route('checkout', [props.service, Object.values(slots.value).find(s => s.datetime === form.datetime).employees[0]]), {}, {
        preserveState: true,
        onSuccess: () => {
            form.employee_id = props.employee.id
        }
    })
})

const pickerRef = ref(null)
let picker = null
const slots = ref([])

const createPicker = () => {
    picker = new easepick.create({
        element: pickerRef.value,
        readonly: true,
        zIndex: 50,
        date: props.date,
        css: [
            style,
            customStyle
        ],
        plugins: [
            LockPlugin
        ],
        LockPlugin: {
            minDate: new Date(),
            filter (date) {
                return !props.availability.find(a => a.date === date.format('YYYY-MM-DD'))
            }
        },
        setup (picker) {
            picker.on('view', (e) => {
                const { view, date, target } = e.detail
                const dateString = date ? date.format('YYYY-MM-DD') : null

                const availability = props.availability.find(a => a.date === dateString)

                if (view === 'CalendarDay' && availability) {
                    const span = target.querySelector('.day-slots') || document.createElement('span')

                    span.className = 'day-slots'
                    span.innerHTML = pluralize('slot', Object.keys(availability.slots).length, true)

                    target.append(span)
                }
            })
        }
    })
}

const setSlots = (date) => {
    slots.value = props.availability.find(a => a.date === date).slots
}

onMounted(() => {
    createPicker()

    setSlots(props.date)

    picker.on('select', (e) => {
        setSlots(e.detail.date.format('YYYY-MM-DD'))
    })

    picker.on('render', (e) => {
        if (e.detail.view === 'Container' && e.detail.date.format('YYYY-MM-DD').toString() !== props.calendar) {
            router.reload({
                data: { calendar: e.detail.date.format('YYYY-MM-DD') },
                only: ['availability', 'calendar', 'date'],
                onSuccess: () => {
                    picker.renderAll()
                }
            })
        }
    })
})
</script>

<template>
    <form class="space-y-10" v-on:submit.prevent="submit">
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
                <div class="grid grid-cols-3 md:grid-cols-5 gap-8">
                    <button type="button" v-on:click="form.datetime = slot.datetime" v-for="slot in slots" :key="slot.datetime" class="py-3 px-4 text-sm border border-slate-200 rounded-lg text-center hover:bg-gray-50/75 cursor-pointer" :class="{ 'bg-slate-100 hover:bg-slate-100': form.datetime === slot.datetime }">
                        {{ slot.time }}
                    </button>
                </div>
            </div>
        </div>

        <div v-if="form.datetime">
            <h2 class="text-xl font-medium">2. Your details and book</h2>

            <div v-if="$page.props.message" class="bg-slate-900 text-white py-4 px-6 rounded-lg mt-3">
                {{ $page.props.message }}
            </div>

            <div class="mt-6">
                <div>
                    <label for="name" class="sr-only">Your name</label>
                    <input type="text" name="name" id="name" class="mt-1 text-sm bg-slate-100 border-0 rounded-lg px-6 py-4 w-full" placeholder="Your name" v-model="form.name">
                </div>
                <div class="mt-3">
                    <label for="email" class="sr-only">Your email</label>
                    <input type="text" name="email" id="email" class="mt-1 text-sm bg-slate-100 border-0 rounded-lg px-6 py-4 w-full" placeholder="Your email" v-model="form.email">
                </div>
                <button type="submit" class="mt-6 py-3 px-6 text-sm border border-slate-200 rounded-lg flex flex-col items-center justify-center text-center hover:bg-slate-900 cursor-pointer bg-slate-800 text-white font-medium">
                    Make booking
                </button>
            </div>
        </div>
    </form>
</template>
