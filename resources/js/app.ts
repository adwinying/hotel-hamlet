import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, DefineComponent, h } from 'vue'

import '../css/app.css'

const app = createInertiaApp({
  resolve(name: string) {
    const pages = import.meta.glob<boolean, string, DefineComponent>(
      './Pages/**/*.vue',
    )

    return pages[`./Pages/${name}.vue`]()
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
  progress: {
    showSpinner: true,
  },
})

export default app
