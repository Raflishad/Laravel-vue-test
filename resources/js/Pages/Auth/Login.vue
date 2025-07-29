<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1">Email</label>
        <input v-model="form.email" type="email" class="w-full border px-3 py-2 rounded" />
        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
      </div>

      <div>
        <label class="block mb-1">Password</label>
        <input v-model="form.password" type="password" class="w-full border px-3 py-2 rounded" />
        <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
        Login
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>
