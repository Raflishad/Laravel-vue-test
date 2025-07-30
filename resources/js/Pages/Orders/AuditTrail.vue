<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Audit Trail Order</h1>

    <table class="table-auto w-full">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Event</th>
          <th>User</th>
          <th>Perubahan</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="audit in audits" :key="audit.id">
          <td>{{ formatDate(audit.created_at) }}</td>
          <td>{{ audit.event }}</td>
          <td>{{ audit.user?.name || '—' }}</td>
          <td>
            <div v-if="audit.old_values && Object.keys(audit.old_values).length">
              <p v-for="(value, key) in audit.old_values" :key="key">
                <strong>{{ key }}:</strong> {{ value }} → {{ audit.new_values[key] }}
              </p>
            </div>
            <div v-else>—</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const props = defineProps({ audits: Array })

function formatDate(date) {
  return new Date(date).toLocaleString()
}
</script>
