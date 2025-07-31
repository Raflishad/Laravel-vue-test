<template>
  <div class="login-page d-flex align-items-center justify-content-center laravel-login-bg" style="min-height: 100vh;">
    <div class="login-box w-100" style="max-width: 400px;">
      <div class="card shadow border-0">
        <div class="card-body login-card-body">
          <h4 class="text-center font-weight-bold mb-4 text-dark">Sign In</h4>

          <form @submit.prevent="submit">
            <!-- Email -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <input
                v-model="form.email"
                type="email"
                class="form-control"
                placeholder="Email"
                required
              />
            </div>
            <small v-if="form.errors.email" class="text-danger d-block mb-2">
              {{ form.errors.email }}
            </small>

            <!-- Password -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <input
                v-model="form.password"
                type="password"
                class="form-control"
                placeholder="Password"
                required
              />
            </div>
            <small v-if="form.errors.password" class="text-danger d-block mb-3">
              {{ form.errors.password }}
            </small>

            <!-- Submit Button -->
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block w-100">
                  <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>

<script>
export default {
  layout: null,
}
</script>

<style scoped>
/* Laravel-style gradient background (red to purple) */
.laravel-login-bg {
  background: linear-gradient(135deg, #ff2d20, #6c2bd9);
}
</style>
