<template>
  <div class="p-4">
    <h1 class="text-2xl fw-bold mb-4">Kategori Produk</h1>

    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header fw-bold">Form Kategori & Export</div>
          <div class="card-body">
            <form @submit.prevent="createCategory" class="mb-4">
              <div class="mb-3">
                <label for="kategori-name" class="form-label fw-semibold">Nama Kategori</label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-tags"></i>
                  </span>
                  <input
                    id="kategori-name"
                    type="text"
                    v-model="form.name"
                    class="form-control"
                    placeholder="Contoh: Elektronik"
                    :class="{ 'is-invalid': errors?.name }"
                  />
                </div>
                <div class="invalid-feedback" v-if="errors?.name">{{ errors.name }}</div>
              </div>

              <div class="mb-3">
                <label for="kategori-description" class="form-label fw-semibold">Deskripsi</label>
                <textarea
                  id="kategori-description"
                  v-model="form.description"
                  class="form-control"
                  placeholder="Contoh: Produk elektronik seperti gadget, alat rumah tangga, dll."
                  rows="3"
                  :class="{ 'is-invalid': errors?.description }"
                ></textarea>
                <div class="invalid-feedback" v-if="errors?.description">{{ errors.description }}</div>
              </div>

              <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
              </button>
            </form>

            <div class="mb-3">
              <label class="form-label fw-semibold">Pilih File untuk Export</label>
              <input
                type="file"
                @change="e => catFile.value = e.target.files[0]"
                accept=".xlsx,.xls"
                class="form-control mb-2"
              />
              <button @click="exportCategory" class="btn btn-success w-100">
                <i class="fas fa-file-export me-1"></i> Export Excel
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Daftar Kategori</span>
            <button @click="importCategory" class="btn btn-primary btn-sm" >
              <i class="fas fa-file-import"></i> Import Excel
            </button>
          </div>

          <div class="px-3 pt-3">
            <div class="row g-2 mb-3">
              <div class="col">
                <input v-model="filters.search" @input="applyFilters" placeholder="Cari produk..." class="form-control" />
              </div>

              <div class="col">
                <select
                  v-model="filters.sort_by"
                  class="form-control"
                  @change="applyFilters"
                >
                  <option value="">Urutkan</option>
                  <option value="name">Nama</option>
                  <option value="release_date">Tanggal Rilis</option>
                </select>
              </div>

              <div class="col">
                <select
                  v-model="filters.sort_dir"
                  class="form-control"
                  @change="applyFilters"
                >
                  <option value="asc">ASC</option>
                  <option value="desc">DESC</option>
                </select>
              </div>
            </div>
          </div>

          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover table-bordered m-0">
              <thead class="table-white">
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="categories.length === 0">
                  <td colspan="5" class="text-center text-muted">Belum ada data</td>
                </tr>
                <tr v-for="(cat, index) in categories" :key="cat.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      v-model="cat.name"
                      class="form-control form-control-sm"
                      @blur="updateCategory(cat)"
                    />
                  </td>
                  <td>
                    <textarea
                      v-model="cat.description"
                      class="form-control form-control-sm"
                      @blur="updateCategory(cat)"
                    ></textarea>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-danger" @click="deleteCategory(cat)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TABEL HISTORICAL -->
<div class="col-12">
  <div class="card">
    <div class="card-header fw-bold">Audit Kategori Terbaru</div>
    <div class="card-body table-responsive p-0">
      <table class="table table-sm table-bordered table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Event</th>
            <th>User</th>
            <th>Kategori</th>
            <th>Perubahan</th>
            <th>Waktu</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="audits.length === 0">
            <td colspan="6" class="text-center text-muted">Tidak ada data audit</td>
          </tr>
          <tr v-for="(audit, index) in audits" :key="audit.id">
            <td>{{ index + 1 }}</td>
            <td>
              <span class="badge"
                :class="{
                  'bg-success': audit.event === 'created',
                  'bg-warning': audit.event === 'updated',
                  'bg-danger': audit.event === 'deleted'
                }">
                {{ audit.event }}
              </span>
            </td>
            <td>{{ audit.user?.name || '—' }}</td>
            <td>{{ audit.auditable?.name || '—' }}</td>
            <td>
              <div v-if="audit.old_values && Object.keys(audit.old_values).length">
                <p v-for="(oldVal, key) in audit.old_values" :key="key">
                  <strong>{{ key }}:</strong> {{ oldVal }} → {{ audit.new_values[key] }}
                </p>
              </div>
              <div v-else>—</div>
            </td>
            <td>{{ formatDate(audit.created_at) }}</td>
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
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })
const props = defineProps({ categories: Array, filters: Object, audits: Array })

// Form untuk tambah kategori
const form = ref({
  name: '',
  description: '',
})

// Error handling jika tersedia (opsional)
const errors = ref({})

// Filters (jika dibutuhkan di halaman ini)
const filters = ref({
  search: '',
  sort_by: '',
  sort_dir: '',
})

function formatDate(date) {
  return new Date(date).toLocaleString('id-ID')
}

function createCategory() {
  router.post('/categories', form.value, {
    onSuccess: () => {
      form.value.name = ''
      form.value.description = ''
    },
    onError: (err) => {
      errors.value = err
    }
  })
}

function applyFilters() {
  router.get('/categories', filters.value, {
    preserveScroll: true,
    preserveState: true,
  })
}

function updateCategory(cat) {
  router.put(`/categories/${cat.id}`, {
    name: cat.name,
    description: cat.description,
  }, { preserveScroll: true })
}

function deleteCategory(cat) {
  if (confirm('Yakin hapus kategori?')) {
    router.delete(`/categories/${cat.id}`, { preserveScroll: true })
  }
}

// Import/export handling
let catFile = ref(null)

function exportCategory() {
  router.post('/categories/export', {}, { preserveScroll: true })
}

function importCategory() {
  const formData = new FormData()
  formData.append('file', catFile.value)

  router.post('/categories/import', formData, {
    forceFormData: true,
    preserveScroll: true,
  })
}
</script>
