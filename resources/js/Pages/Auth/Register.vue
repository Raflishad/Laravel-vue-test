<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>
    
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Name</label>
        <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label class="block mb-1">Email</label>
        <input v-model="form.email" type="email" class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label class="block mb-1">Password</label>
        <input v-model="form.password" type="password" class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label class="block mb-1">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" class="w-full border px-3 py-2 rounded" />
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
        Register
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post('/register')
}
</script>

  <script>
  export default {
    layout: null,
  }
  </script>