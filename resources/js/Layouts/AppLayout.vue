<template>
  <div class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <form @submit.prevent="logout">
              <button class="btn btn-sm btn-danger">Logout</button>
            </form>
          </li>
        </ul>
      </nav>

      <!-- Sidebar -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/" class="brand-link">
          <!-- <img src="#" alt="Logo" class="brand-image img-circle elevation-3" /> -->
          <span class="brand-text font-weight-light">Product Admin</span>
        </a>

        <div class="sidebar">
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
              <li class="nav-item"><NavLink href="/dashboard" label="Dashboard" /></li>
              <li class="nav-item"><NavLink href="/products" label="Products" /></li>
              <li class="nav-item"><NavLink href="/categories" label="Categories" /></li>
              <li class="nav-item"><NavLink href="/orders" label="Orders" /></li>
              <li class="nav-item"><NavLink href="/roles" label="Roles" /></li>
              <li class="nav-item"><NavLink href="/users" label="Users" /></li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Content -->
      <div class="content-wrapper p-4">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'

function logout() {
  router.post('/logout')
}
</script>
