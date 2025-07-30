<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Produk</h1>

    <form @submit.prevent="createProduct" class="space-y-2 mb-4">
      <input v-model="form.name" placeholder="Nama produk" class="input" />
      <input v-model="form.price" type="number" placeholder="Harga" class="input" />
      <textarea v-model="form.description" placeholder="Deskripsi" class="input" />
      <input v-model="form.release_date" type="datetime-local" class="input" />
      <select v-model="form.category_id" class="input">
        <option disabled value="">Pilih Kategori</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
      <input v-model="form.tags_string" placeholder="Tag (pisah koma)" class="input" />
      <label>
        <input type="checkbox" v-model="form.is_active" />
        Aktif
      </label>
      <button class="btn">Tambah Produk</button>
    </form>

    <table class="table-auto w-full">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Aktif</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <tr v-for="product in products" :key="product.id">
        <td>{{ product.name }}</td>
        <td>{{ product.category?.name }}</td>
        <td>{{ product.price }}</td>
        <td>{{ product.is_active ? 'Ya' : 'Tidak' }}</td>
        <td>
          <form @submit.prevent="uploadFile(product.id, product.file)" enctype="multipart/form-data">
            <input type="file" @change="e => product.file = e.target.files[0]" accept="application/pdf" />
            <button>Upload</button>
          </form>
          <ul>
            <li v-for="file in product.files" :key="file.id">
              <a :href="`/storage/${file.file_path}`" target="_blank">Lihat File</a>
              <a :href="`/products/${product.id}/audit`" class="text-blue-500">Lihat Audit</a>
              <button @click="deleteFile(file.id)">Hapus</button>
            </li>
          </ul>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="mb-4 space-x-2">
    <input v-model="filters.search" placeholder="Cari nama produk..." class="input" @input="applyFilters" />
    
    <select v-model="filters.category_id" class="input" @change="applyFilters">
      <option value="">Semua Kategori</option>
      <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
    </select>

    <select v-model="filters.sort_by" class="input" @change="applyFilters">
      <option value="">Urutkan</option>
      <option value="name">Nama</option>
      <option value="price">Harga</option>
      <option value="release_date">Tanggal Rilis</option>
    </select>

    <select v-model="filters.sort_dir" class="input" @change="applyFilters">
      <option value="asc">Naik</option>
      <option value="desc">Turun</option>
    </select>
  </div>

  <form @submit.prevent="export">
  <label v-for="field in exportFields" :key="field">
    <input type="checkbox" v-model="selectedFields" :value="field" /> {{ field }}
  </label>
  <button class="btn">Export Excel</button>
</form>

<form @submit.prevent="importExcel" enctype="multipart/form-data">
  <input type="file" @change="e => file = e.target.files[0]" accept=".xlsx,.xls" />
  <button class="btn">Import Excel</button>
</form>
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
