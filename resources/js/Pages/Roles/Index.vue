<template>
  <div class="p-4">
    <h1 class="text-2xl fw-bold mb-4">Kategori Produk</h1>

  <div class="container">
    <div class="row">
      <!-- Form Tambah Role -->
      <div class="col-md-5">
        <div class="card mb-4">
          <div class="card-header fw-bold">
            Tambah Role
          </div>
          <div class="card-body">
            <form @submit.prevent="createRole">
              <div class="mb-3">
                <label class="form-label">Nama Role</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  placeholder="Contoh: Admin"
                  required
                />
              </div>
              <button class="btn btn-primary w-100">
                <i class="fas fa-plus me-1"></i> Tambah Role
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Daftar Role -->
      <div class="col-md-7">
        <div class="card">
          <div class="card-header fw-bold">
            Daftar Role
          </div>
          <div class="card-body p-0">
            <table class="table table-striped m-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Nama Role</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(role, index) in roles" :key="role.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      v-model="role.name"
                      @blur="updateRole(role)"
                      class="form-control"
                    />
                  </td>
                  <td class="text-end">
                    <button
                      class="btn btn-sm btn-danger"
                      @click="deleteRole(role)"
                    >
                      <i class="fas fa-trash-alt me-1"></i> Hapus
                    </button>
                  </td>
                </tr>
                <tr v-if="roles.length === 0">
                  <td colspan="3" class="text-center text-muted p-3">
                    Belum ada role ditambahkan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
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
  router.post('/roles', form.value, {
    onSuccess: () => {
      form.value.name = '' 
    }
  })
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
