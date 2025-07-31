<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Order</h1>

    <div class="row">
      <!-- FORM -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header fw-bold">
            {{ editing ? 'Edit Order' : 'Buat Order' }}
          </div>
          <div class="card-body">
            <form @submit.prevent="submit" class="space-y-3">
              <div class="mb-3">
                <label class="form-label fw-semibold mb-2">Pilih Produk</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-box"></i></span>
                  <select v-model="form.product_id" class="form-control" required>
                    <option disabled value="">-- Pilih Produk --</option>
                    <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold mb-2">Jumlah</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                  <input v-model="form.quantity" type="number" class="form-control" min="1" required />
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-1"></i> {{ editing ? 'Update' : 'Tambah' }} Order
              </button>

              <button v-if="editing" type="button" class="btn btn-secondary w-100 mt-2" @click="resetForm">
                Batal Edit
              </button>
            </form>

            <hr class="my-4" />

            <!-- Export Section -->
            <div>
              <label class="form-label fw-semibold mb-2">Export Data Order</label>
              <input type="file" @change="e => orderFile.value = e.target.files[0]" accept=".xlsx,.xls"
                class="form-control mb-2" />
              <button @click="exportOrder" class="btn btn-success w-100">
                <i class="fas fa-file-export me-1"></i> Export Excel
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- TABLE -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Daftar Order</span>
            <button @click="importOrder" class="btn btn-sm btn-primary" :disabled="orders.length === 0">
              <i class="fas fa-file-import me-1"></i> Import Excel
            </button>
          </div>

          <!-- FILTER BAR -->
          <div class="p-3 border-bottom">
            <div class="row g-2">
              <div class="col-md-4">
                <input
                  type="text"
                  class="form-control"
                  v-model="filters.search"
                  @input="applyFilters"
                  placeholder="Cari produk..."
                />
              </div>
              <div class="col-md-4">
                <select v-model="filters.sort_by" class="form-control" @change="applyFilters">
                  <option value="">Urutkan</option>
                  <option value="product_name">Nama</option>
                  <option value="quantity">Jumlah</option>
                  <option value="total_price">Total</option>
                </select>
              </div>
              <div class="col-md-4">
                <select v-model="filters.sort_dir" class="form-control" @change="applyFilters">
                  <option value="asc">Naik (ASC)</option>
                  <option value="desc">Turun (DESC)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(o, index) in orders" :key="o.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                      <select v-model="o.product_id" class="form-select" @change="saveEdit(o)">
                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                      </select>
                    </td>
                    <td>Rp {{ o.product_price }}</td>
                    <td>
                      <input v-model.number="o.quantity" type="number" min="1" class="form-control" @change="saveEdit(o)" />
                    </td>
                    <td>Rp {{ o.total_price }}</td>
                    <td>
                      <button class="btn btn-sm btn-danger" @click="deleteOrder(o.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  <tr v-if="orders.length === 0">
                    <td colspan="7" class="text-center text-muted">Belum ada data order</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- AUDIT TRAIL -->
<div class="col-12">
  <div class="card mt-4">
    <div class="card-header fw-bold">Audit Order Terbaru</div>
    <div class="card-body p-0">
      <table class="table table-sm table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Event</th>
            <th>User</th>
            <th>Order</th>
            <th>Perubahan</th>
            <th>Waktu</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="audits.length === 0">
            <td colspan="6" class="text-center text-muted">Belum ada audit order</td>
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
            <td>{{ audit.auditable?.order_number || '—' }}</td>
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
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
  orders: Array,
  products: Array,
  audits: Array,
})

// Form & State
const form = ref({ id: null, product_id: '', quantity: 1 })
const editing = ref(false)
const orderFile = ref(null)

// Filters
const filters = ref({
  search: '',
  sort_by: '',
  sort_dir: 'asc',
})

// Filter handler
function applyFilters() {
  router.get('/orders', filters.value, {
    preserveScroll: true,
    preserveState: true,
  })
}

function formatDate(date) {
  return new Date(date).toLocaleString()
}

// Form submission
function submit() {
  if (editing.value) {
    router.put(`/orders/${form.value.id}`, form.value, {
      onSuccess: () => resetForm(),
    })
  } else {
    router.post('/orders', form.value, {
      onSuccess: () => resetForm(),
    })
  }
}

function resetForm() {
  form.value = { id: null, product_id: '', quantity: 1 }
  editing.value = false
}

// Inline update
function saveEdit(order) {
  router.put(`/orders/${order.id}`, {
    product_id: order.product_id,
    quantity: order.quantity,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      const product = props.products.find(p => p.id === order.product_id)
      if (product) {
        order.product_price = product.price
        order.total_price = product.price * order.quantity
      }
    }
  })
}

// Delete order
function deleteOrder(id) {
  if (confirm('Yakin ingin menghapus order ini?')) {
    router.delete(`/orders/${id}`, {
      preserveScroll: true,
    })
  }
}

// Export & Import
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
