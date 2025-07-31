// Bootstrap & Vite base setup
import './bootstrap'

// Vue & Inertia setup
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Layout default untuk semua halaman
import DefaultLayout from '@/Layouts/AppLayout.vue'

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
