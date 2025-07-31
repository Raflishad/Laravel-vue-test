<template>
  <div class="d-flex align-items-center justify-content-center min-vh-100 px-3 laravel-bg">
    <div class="text-center text-white">
      <h1 class="display-4 font-weight-bold mb-3">
        Welcome to the <span class="font-weight-bold">Product Management System</span>
      </h1>
      <p class="lead mb-4">
        Streamline your workflow and manage your products with confidence.
      </p>
      <a href="/dashboard" class="btn btn-light btn-lg px-5 shadow-sm font-weight-semibold">
        Get Started
      </a>
    </div>
  </div>
</template>

<script>
export default {
  layout: null,
}
</script>

<style scoped>
.laravel-bg {
  background: linear-gradient(135deg, #ff2d20, #6c2bd9);
}

.text-center {
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
