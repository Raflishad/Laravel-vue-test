<template>
  <div>
    <div class="d-flex">
    <h1 class="text-2xl font-bold mr-2">Dashboard</h1>
    <h3 class="text-muted mt-2">/ {{ user?.role ?? 'No Role' }}</h3>
    </div>
    <div class="p-4">
    <div class="card col-6 p-4">
    <h2>Hai, {{ user?.name ?? 'Viewer' }}!</h2>
        <h4>Selamat datang di dashboard aplikasi!</h4>
        <h4>Anda dapat mengelola produk, kategori, dan order di sini.</h4>
    </div>
  </div>
  </div>
</template>

<script setup>
import { defineOptions } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'
defineOptions({ layout: AppLayout })

const page = usePage()
const user = page.props.auth.user

</script>
