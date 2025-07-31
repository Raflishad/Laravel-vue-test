<template>
  <a :href="href" class="nav-link" :class="{ active: isActive }">
    <i v-if="icon" :class="['nav-icon', icon]"></i>
    <p>{{ label }}</p>
  </a>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  href: String,
  label: String,
  icon: String
})

const page = usePage()
const isActive = computed(() => page.url.startsWith(props.href))
</script>
