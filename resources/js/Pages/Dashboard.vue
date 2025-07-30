<template>
  <div>
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <form method="POST" @submit.prevent="logout">
      <button class="text-red-500 mt-4">Logout</button>
    </form>
  </div>
</template>

<script setup>
import { defineOptions } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
function logout() {
  router.post('/logout')
}
</script>
