// Bootstrap & Vite base setup
import './bootstrap'

// Vue & Inertia setup
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Layout default untuk semua halaman
import DefaultLayout from '@/Layouts/AppLayout.vue'

// --- AdminLTE Assets ---
// AdminLTE styles
import 'admin-lte/dist/css/adminlte.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'

// Bootstrap JS (required by AdminLTE)
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// AdminLTE JS
import 'admin-lte/dist/js/adminlte.min.js'

// --- Inertia App Initialization ---
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page = pages[`./Pages/${name}.vue`]

    // Terapkan layout default jika tidak diset manual
    if (page.default.layout === undefined) {
      page.default.layout = DefaultLayout
    }
    return page
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  }
})
