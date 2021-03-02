import { createApp, h } from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import Layout from './components/Layout.vue'

const el = document.getElementById('app')

createApp({
  render: () => h(App, {
    initialPage: JSON.parse(el?.dataset.page ?? ''),
    resolveComponent: (name: string) => import(`./Pages/${name}`)
      .then(({ default: page }) => {
        if (page.layout === undefined) {
          // eslint-disable-next-line no-param-reassign
          page.layout = Layout
        }

        return page
      }),
  }),
})
  .use(plugin)
  .mount(el ?? '#app')

InertiaProgress.init({
  showSpinner: true,
})
