import { createApp, h } from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import '../css/app.css'
import Page from './components/Page.vue'

const el = document.getElementById('app')

const app = createApp({
  render: () => h(App, {
    initialPage: JSON.parse(el?.dataset.page ?? ''),
    resolveComponent: async (name: string) => {
      const pages = import.meta.glob('./Pages/**/*.vue')
      const module = await pages[`./Pages/${name}.vue`]()
      const page = module.default

      return page
    },
  }),
})

app.use(plugin)
app.mount(el ?? '#app')
app.component('Page', Page)

InertiaProgress.init({
  showSpinner: true,
})

export default app
