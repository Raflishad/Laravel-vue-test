<template>
  <div class="register-page bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="register-box w-100" style="max-width: 400px;">
      <div class="card shadow border-0">
        <div class="card-body register-card-body">
          <h4 class="text-center fw-bold mb-4">Register</h4>

          <form @submit.prevent="submit">
            <!-- Name -->
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-user"></i>
              </span>
              <input
                v-model="form.name"
                type="text"
                class="form-control"
                placeholder="Full Name"
                required
              />
            </div>
            <small v-if="form.errors.name" class="text-danger d-block mb-2">
              {{ form.errors.name }}
            </small>

            <!-- Email -->
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-envelope"></i>
              </span>
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
              <span class="input-group-text">
                <i class="fas fa-lock"></i>
              </span>
              <input
                v-model="form.password"
                type="password"
                class="form-control"
                placeholder="Password"
                required
              />
            </div>
            <small v-if="form.errors.password" class="text-danger d-block mb-2">
              {{ form.errors.password }}
            </small>

            <!-- Confirm Password -->
            <div class="input-group mb-3">
              <span class="input-group-text">
                <i class="fas fa-lock"></i>
              </span>
              <input
                v-model="form.password_confirmation"
                type="password"
                class="form-control"
                placeholder="Confirm Password"
                required
              />
            </div>
            <small v-if="form.errors.password_confirmation" class="text-danger d-block mb-2">
              {{ form.errors.password_confirmation }}
            </small>

            <!-- Register Button -->
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block w-100">
                  <i class="fas fa-user-plus me-1"></i> Register
                </button>
              </div>
            </div>
          </form>

          <p class="mb-0 mt-3 text-center">
            <a href="/login" class="text-decoration-none">Sudah punya akun? Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post('/register')
}
</script>

<script>
export default {
  layout: null,
}
</script>
