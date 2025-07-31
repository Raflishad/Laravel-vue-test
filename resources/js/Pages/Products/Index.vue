<template>
  <div class="p-4">
    <h1 class="text-2xl fw-bold mb-4">Manajemen Produk</h1>

    <div class="row">
      <!-- KIRI: Form + Export -->
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header fw-bold">Form Produk & Export</div>
          <div class="card-body">
            <form @submit.prevent="createProduct" class="mb-4">
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input v-model="form.name" class="form-control" placeholder="Nama produk" required />
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Harga</label>
                <div class="input-group">
                <span class="input-group-text">
                  <span class="">Rp</span>
                </span>
                <input v-model="form.price" type="number" class="form-control" placeholder="Harga" required />
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea v-model="form.description" class="form-control" placeholder="Deskripsi" required></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Rilis</label>
                <input v-model="form.release_date" type="datetime-local" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-tag"></i>
                  </span>
                  <select
                    v-model="form.category_id"
                    class="form-control"
                    required
                  >
                    <option disabled value="">-- Pilih Kategori --</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                      {{ cat.name }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Tag</label>
                <input v-model="form.tags_string" placeholder="Tag (pisah koma)" class="form-control" />
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="form.is_active" id="activeCheck" />
                <label class="form-check-label ml-1" for="activeCheck">Aktif</label>
                </div>
              </div>

              <button class="btn btn-primary w-100 mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Produk
              </button>
            </form>

            <!-- Export Section -->
            <div>
              <label class="form-label fw-semibold mb-2">Export Data Produk</label>

              <!-- Input file -->
              <input
                type="file"
                @change="e => productFile = e.target.files[0]"
                accept=".xlsx,.xls"
                class="form-control mb-2"
              />

              <!-- Tombol Export -->
              <button
                @click="export"
                class="btn btn-success w-100"
              >
                <i class="fas fa-file-export me-1"></i> Export Excel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- KANAN: Tabel + Filter + Import -->
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Daftar Produk</span>
            <button
              @click="importProduct"
              class="btn btn-sm btn-primary"
              :disabled="products.length === 0"
            >
              <i class="fas fa-file-import me-1"></i> Import Excel
            </button>
          </div>

          <!-- FILTER -->
          <div class="px-3 pt-3">
            <div class="row g-2 mb-3">
              <div class="col">
                <input v-model="filters.search" @input="applyFilters" placeholder="Cari produk..." class="form-control" />
              </div>
              <div class="col">
                <select
                  v-model="filters.category_id"
                  class="form-control"
                  @change="applyFilters"
                >
                  <option value="">Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>

              <div class="col">
                <select
                  v-model="filters.sort_by"
                  class="form-control"
                  @change="applyFilters"
                >
                  <option value="">Urutkan</option>
                  <option value="name">Nama</option>
                  <option value="price">Harga</option>
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

          <!-- TABLE -->
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-bordered table-hover mb-0">
              <thead class="table-white">
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Aktif</th>
                  <th>File</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="products.length === 0">
                  <td colspan="8" class="text-center text-muted">Belum ada data</td>
                </tr>
                <tr v-for="(product, index) in products" :key="product.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.category?.name }}</td>
                  <td>{{ product.price }}</td>
                  <td>{{ product.is_active ? 'Ya' : 'Tidak' }}</td>
                  <td>
                    <form @submit.prevent="uploadFile(product.id, product.file)" enctype="multipart/form-data" class="mb-2">
                      <input type="file" @change="e => product.file = e.target.files[0]" accept="application/pdf" class="form-control form-control-sm mb-1" />
                      <ul class="list-unstyled mb-0 py-1">
                        <button class="btn btn-sm btn-primary"><i class="fas fa-upload"></i></button>
                        <li v-for="file in product.files" :key="file.id" class="d-flex justify-content-between align-items-center">
                          <a :href="`/storage/${file.file_path}`" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                          <button @click="deleteFile(file.id)" class="btn btn-sm btn-link btn-danger text-white ms-2"><i class="fas fa-trash"></i></button>
                        </li>
                      </ul>
                    </form>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-danger" @click="deleteProduct(product)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- AUDIT TRAIL -->
       <div class="col-12">
<div class="card">
  <div class="card-header fw-bold">Audit Produk Terbaru</div>
  <div class="card-body p-0">
    <table class="table table-sm table-striped mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Event</th>
          <th>User</th>
          <th>Produk</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="props.audits.length === 0">
          <td colspan="5" class="text-center text-muted">Belum ada aktivitas audit</td>
        </tr>
        <tr v-for="(audit, index) in props.audits" :key="audit.id">
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
          <td>{{ audit.user?.name || 'System' }}</td>
          <td>{{ audit.auditable?.name || '-' }}</td>
          <td>{{ new Date(audit.created_at).toLocaleString() }}</td>
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
import { ref, reactive, watch } from 'vue'

defineOptions({ layout: AppLayout })
const props = defineProps({
  products: Array,
  categories: Array,
  filters: Object,
  audits: Array,
})

const filters = ref({
  search: props.filters.search || '',
  category_id: props.filters.category_id || '',
  sort_by: props.filters.sort_by || '',
  sort_dir: props.filters.sort_dir || 'asc',
})

const form = reactive({
  name: '',
  description: '',
  price: 0,
  is_active: true,
  tags: [],
  tags_string: '',
  release_date: '',
  category_id: '',
})

function applyFilters() {
  router.get('/products', filters.value, {
    preserveScroll: true,
    preserveState: true,
  })
}

function formatDate(date) {
  return new Date(date).toLocaleString()
}

watch(() => form.tags_string, val => {
  form.tags = val.split(',').map(t => t.trim())
})

function createProduct() {
  router.post('/products', form)
}

function deleteProduct(product) {
  if (confirm('Yakin ingin hapus produk?')) {
    router.delete(`/products/${product.id}`)
  }
}

function uploadFile(productId, file) {
  const formData = new FormData()
  formData.append('file', file)
  router.post(`/products/${productId}/files`, formData, {
    forceFormData: true,
    preserveScroll: true
  })
}

function deleteFile(fileId) {
  if (confirm('Yakin hapus file?')) {
    router.delete(`/product-files/${fileId}`, { preserveScroll: true })
  }
}

const exportFields = ['name', 'price', 'is_active', 'release_date']
const selectedFields = ref([])
let file = ref(null)

function exportData() {
  router.post('/products/export', { fields: selectedFields.value }, { preserveScroll: true })
}

function importExcel() {
  const formData = new FormData()
  formData.append('file', file.value)
  router.post('/products/import', formData, {
    forceFormData: true,
    preserveScroll: true
  })
}

</script>
