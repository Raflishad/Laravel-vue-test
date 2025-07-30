<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Role Management</h1>

    <form @submit.prevent="createRole">
      <input v-model="form.name" type="text" placeholder="Role name" class="input mb-2" />
      <button class="btn">Create Role</button>
    </form>

    <ul class="mt-4">
      <li v-for="role in roles" :key="role.id" class="flex items-center justify-between">
        <div>
          <input v-model="role.name" @blur="updateRole(role)" class="input" />
        </div>
        <button class="text-red-500" @click="deleteRole(role)">Hapus</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
defineOptions({ layout: AppLayout })
defineProps({ roles: Array })

const form = ref({ name: '' })

function createRole() {
  router.post('/roles', form.value)
}

function updateRole(role) {
  router.put(`/roles/${role.id}`, { name: role.name }, { preserveScroll: true })
}

function deleteRole(role) {
  if (confirm('Yakin ingin menghapus role ini?')) {
    router.delete(`/roles/${role.id}`, { preserveScroll: true })
  }
}
</script>
