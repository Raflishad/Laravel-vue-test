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

        <ul class="navbar-nav ml-auto px-3">
          <li class="nav-item">
            <form @submit.prevent="logout">
              <button type="submit" class="btn btn-sm btn-danger">Logout</button>
            </form>
          </li>
        </ul>
      </nav>

      <!-- Sidebar -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <div href="/" class="brand-link">
          <span class="brand-text">Product App</span>
        </div>

        <div class="sidebar">
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
              <li class="nav-item"><NavLink href="/dashboard" label="Dashboard" icon="fas fa-tachometer-alt" /></li>
              <li class="nav-item"><NavLink href="/products" label="Products" icon="fas fa-boxes" /></li>
              <li class="nav-item"><NavLink href="/categories" label="Categories" icon="fas fa-tags" /></li>
              <li class="nav-item"><NavLink href="/orders" label="Orders" icon="fas fa-shopping-cart" /></li>
              <li class="nav-item"><NavLink href="/roles" label="Roles" icon="fas fa-user-shield" /></li>
              <li class="nav-item"><NavLink href="/users" label="Users" icon="fas fa-users" /></li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Content -->
      <div class="content-wrapper p-4">
        <slot />
      </div>

      <!-- Footer -->
      <footer class="main-footer">
        <strong>&copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 3.2.0
        </div>
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark"></aside>
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
