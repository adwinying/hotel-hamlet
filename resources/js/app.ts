import { createApp, h } from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import Page from './components/Page.vue'

const el = document.getElementById('app')

const app = createApp({
  render: () => h(App, {
    initialPage: JSON.parse(el?.dataset.page ?? ''),
    resolveComponent: (name: string) => import(`./Pages/${name}`)
      .then((module) => module.default),
  }),
})

app.use(plugin)
app.mount(el ?? '#app')
app.component('Page', Page)

InertiaProgress.init({
  showSpinner: true,
})

export default app
