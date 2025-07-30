<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kategori Produk</h1>

    <form @submit.prevent="createCategory" class="mb-4 space-y-2">
      <input v-model="form.name" placeholder="Nama kategori" class="input" />
      <textarea v-model="form.description" placeholder="Deskripsi" class="input" />
      <button class="btn">Tambah Kategori</button>
    </form>

    <ul>
      <li v-for="cat in categories" :key="cat.id" class="mb-2">
        <input v-model="cat.name" class="input" @blur="updateCategory(cat)" />
        <textarea v-model="cat.description" class="input" @blur="updateCategory(cat)" />
        <button class="text-red-500" @click="deleteCategory(cat)">Hapus</button>
      </li>
    </ul>

    <!-- Export/Import Category -->
    <div class="mb-4 space-x-2">
    <button @click="exportCategory" class="btn">Export Excel</button>

    <form @submit.prevent="importCategory" enctype="multipart/form-data" class="inline">
        <input type="file" @change="e => catFile = e.target.files[0]" accept=".xlsx,.xls" class="input inline-block" />
        <button class="btn ml-2">Import</button>
    </form>
    </div>

  </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })
const props = defineProps({ categories: Array })

const form = ref({
  name: '',
  description: '',
})

function createCategory() {
  router.post('/categories', form.value)
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
