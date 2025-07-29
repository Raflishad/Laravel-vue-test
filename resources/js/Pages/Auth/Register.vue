<template>
  <form @submit.prevent="submit">
    <h1 class="text-xl font-bold mb-4">Register</h1>
    <input v-model="form.name" type="text" placeholder="Name" class="input" />
    <input v-model="form.email" type="email" placeholder="Email" class="input" />
    <input v-model="form.password" type="password" placeholder="Password" class="input" />
    <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="input" />
    <button type="submit" class="btn">Register</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post('/register')
}
</script>
