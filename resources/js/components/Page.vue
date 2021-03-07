<template>
  <div class="bg-gray-50">
    <page-sidebar
      :items="sidebarItems"
      :is-active="isActive"
      :active-item="activeItem"
      class="fixed top-0 left-0 z-50" />

    <div class="pl-72 min-h-screen">
      <page-title v-if="title">
        {{ title }}
      </page-title>

      <page-content>
        <slot />
      </page-content>
    </div>
  </div>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  ref,
} from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import SidebarItem from '../types/SidebarItem'
import PageSidebar from './PageSidebar.vue'
import PageTitle from './PageTitle.vue'
import PageContent from './PageContent.vue'

interface CommonPageProps {
  sidebarItems?: SidebarItem[];
}
export default defineComponent({
  name: 'Page',

  components: {
    PageSidebar,
    PageTitle,
    PageContent,
  },

  props: {
    title: {
      type: String,
      default: null,
    },
  },

  setup() {
    const { props: pageProps, url } = usePage()

    const sidebarItems = computed(
      () => (pageProps.value as CommonPageProps).sidebarItems ?? [],
    )

    const activeItem = computed(() => {
      const path = url.value

      if (/^\/admin\/hotels.*$/.test(path)) return 'Hotels'
      if (/^\/admin\/rooms.*$/.test(path)) return 'Rooms'
      if (/^\/admin\/reservations.*$/.test(path)) return 'Reservations'
      if (/^\/admin$/.test(path)) return 'Dashboard'

      return null
    })

    const isActive = ref(true)

    const pageTitle = computed(() => url)

    return {
      sidebarItems,
      isActive,
      activeItem,
      pageTitle,
    }
  },
})
</script>
