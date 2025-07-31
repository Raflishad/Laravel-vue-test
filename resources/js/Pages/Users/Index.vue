<template>
  <div class="p-4">
    <h1 class="text-2xl fw-bold mb-4">Manajemen User</h1>

    <div class="row">
      <div class="col-md-5 mb-4">
        <div class="card">
          <div class="card-header fw-bold">Tambah User</div>
          <div class="card-body">
            <form @submit.prevent="createUser" class="space-y-3">
              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input v-model="form.name" type="text" class="form-control" placeholder="Nama" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" placeholder="Email" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input v-model="form.password" type="password" class="form-control" placeholder="Password" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Role</label>
                <select v-model="form.role" class="form-control" required>
                  <option disabled value="">-- Pilih Role --</option>
                  <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>
              </div>

              <button class="btn btn-primary w-100">
                <i class="fas fa-user-plus me-1"></i> Tambah User
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-7 mb-4">
        <div class="card">
          <div class="card-header fw-bold">Daftar User</div>
          <div class="card-body p-0">
            <table class="table table-striped table-bordered m-0">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="users.length === 0">
                  <td colspan="5" class="text-center text-muted p-3">Belum ada user</td>
                </tr>
                <tr v-for="(user, index) in users" :key="user.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input v-model="user.name" class="form-control form-control-sm" />
                  </td>
                  <td>
                    <input v-model="user.email" class="form-control form-control-sm" />
                  </td>
                  <td>
                    <select v-model="user.roles[0].name" class="form-control form-control-sm">
                      <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                    </select>
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <button class="btn btn-sm btn-success" @click="updateUser(user)">
                        <i class="fas fa-save"></i>
                      </button>
                      <button class="btn btn-sm btn-danger" @click="deleteUser(user)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
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
  router.post('/users', form.value, {
    onSuccess: () => {
      form.value = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: ''
      }
    }
  })
}

function updateUser(user) {
  router.put(`/users/${user.id}`, {
    name: user.name,
    email: user.email,
    role: user.roles[0].name,
  }, { preserveScroll: true })
}

function deleteUser(user) {
  if (confirm('Yakin ingin menghapus user ini?')) {
    router.delete(`/users/${user.id}`, { preserveScroll: true })
  }
}
</script>
