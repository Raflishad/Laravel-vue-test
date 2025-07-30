
<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    <form @submit.prevent="createUser" class="space-y-2 mb-6">
      <input v-model="form.name" type="text" placeholder="Name" class="input" />
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <input v-model="form.password" type="password" placeholder="Password" class="input" />
      <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="input" />
      <select v-model="form.role" class="input">
        <option disabled value="">Pilih Role</option>
        <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
      </select>
      <button class="btn">Tambah User</button>
    </form>

    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="text-left">Nama</th>
          <th class="text-left">Email</th>
          <th class="text-left">Role</th>
          <th class="text-left">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td><input v-model="user.name" class="input" /></td>
          <td><input v-model="user.email" class="input" /></td>
          <td>
            <select v-model="user.roles[0].name" class="input">
              <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
            </select>
          </td>
          <td>
            <button class="text-blue-600" @click="updateUser(user)">Update</button>
            <button class="text-red-600 ml-2" @click="deleteUser(user)">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
const props = defineProps({
  users: Array,
  roles: Array,
})

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
})

function createUser() {
  router.post('/users', form.value)
}

function updateUser(user) {
  router.put(`/users/${user.id}`, {
    name: user.name,
    email: user.email,
    role: user.roles[0].name,
  })
}

function deleteUser(user) {
  if (confirm('Yakin ingin menghapus user ini?')) {
    router.delete(`/users/${user.id}`)
  }
}
</script>
