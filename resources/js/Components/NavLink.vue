<template>
  <li class="nav-item">
    <Link :href="href"
          class="nav-link"
          :class="{ active: isActive }">
      <i v-if="icon" :class="['nav-icon', icon]"></i>
      <p>{{ label }}</p>
    </Link>
  </li>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  href: String,
  label: String,
  icon: String
})

const currentUrl = usePage().url
const isActive = computed(() => currentUrl.startsWith(props.href))
</script>
