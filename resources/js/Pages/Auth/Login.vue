<template>
  <form @submit.prevent="submit">
    <h1 class="text-xl font-bold mb-4">Login</h1>
    <input v-model="form.email" type="email" placeholder="Email" class="input" />
    <input v-model="form.password" type="password" placeholder="Password" class="input" />
    <button type="submit" class="btn">Login</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})

function submit() {
  form.post('/login')
}
</script>
