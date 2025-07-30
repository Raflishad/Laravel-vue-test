<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Order</h1>

    <form @submit.prevent="submit">
      <select v-model="form.product_id" class="input">
        <option disabled value="">Pilih Produk</option>
        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
      </select>
      <input v-model="form.quantity" type="number" placeholder="Jumlah" class="input" />
      <button class="btn">Buat Order</button>
    </form>

    <table class="table-auto w-full mt-6">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Total</th>
          <th>Audit</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="o in orders" :key="o.id">
          <td>{{ o.product_name }}</td>
          <td>{{ o.product_price }}</td>
          <td>{{ o.quantity }}</td>
          <td>{{ o.total_price }}</td>
          <td>
            <a :href="`/orders/${o.id}/audit`" class="text-blue-500">Lihat</a>
          </td>
        </tr>
      </tbody>
    </table>


    <!-- Export/Import Order -->
    <div class="mb-4 space-x-2">
    <button @click="exportOrder" class="btn">Export Excel</button>

    <form @submit.prevent="importOrder" enctype="multipart/form-data" class="inline">
        <input type="file" @change="e => orderFile = e.target.files[0]" accept=".xlsx,.xls" class="input inline-block" />
        <button class="btn ml-2">Import</button>
    </form>
    </div>

  </div>


</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
const props = defineProps({
  orders: Array,
  products: Array,
})

const form = ref({
  product_id: '',
  quantity: 1,
})

function submit() {
  router.post('/orders', form.value)
}

let orderFile = ref(null)

function exportOrder() {
  router.post('/orders/export', {}, { preserveScroll: true })
}

function importOrder() {
  const formData = new FormData()
  formData.append('file', orderFile.value)
  router.post('/orders/import', formData, {
    forceFormData: true,
    preserveScroll: true,
  })
}

</script>
