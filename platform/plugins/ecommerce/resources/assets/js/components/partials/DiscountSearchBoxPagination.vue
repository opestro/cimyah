<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        resource: Object,
    },
    emits: ['onPrev', 'onNext']
})
</script>

<template>
    <ul class="pagination my-0 d-flex justify-content-end">
        <li :class="{'page-item': true, disabled: resource.current_page === 1}">
            <span v-if="resource.current_page === 1" class="page-link" :aria-disabled="resource.current_page === 1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </span>
            <a
                v-else
                href="javascript:void(0)"
                class="page-link"
                @click="$emit('onPrev')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </a>
        </li>
        <li :class="{'page-item': true, disabled: !resource.next_page_url}">
            <span v-if="!resource.next_page_url" class="page-link" :aria-disabled="!resource.next_page_url">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
            </span>
            <a
                v-else
                href="javascript:void(0)"
                class="page-link"
                @click="$emit('onNext')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
            </a>
        </li>
    </ul>
</template>
