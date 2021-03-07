<template>
  <div class="bg-gray-50">
    <page-sidebar
      :items="sidebarItems"
      :is-active="isActive"
      :active-item="activeItem"
      class="fixed top-0 left-0 z-50" />

    <div class="pl-72 min-h-screen">
      <page-header v-if="header">
        {{ title }}

        <template #pre-title>
          <slot name="pre-title" />
        </template>

        <template #post-title>
          <slot name="post-title" />
        </template>
      </page-header>

      <page-content>
        <page-card v-if="card">
          <slot />
        </page-card>
        <template v-else>
          <slot />
        </template>
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
import PageHeader from './PageHeader.vue'
import PageContent from './PageContent.vue'
import PageCard from './PageCard.vue'

interface CommonPageProps {
  sidebarItems?: SidebarItem[];
}
export default defineComponent({
  name: 'Page',

  components: {
    PageSidebar,
    PageHeader,
    PageContent,
    PageCard,
  },

  props: {
    header: {
      type: Boolean,
      default: true,
    },

    title: {
      type: String,
      default: null,
    },

    card: {
      type: Boolean,
      default: true,
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

    return {
      sidebarItems,
      isActive,
      activeItem,
    }
  },
})
</script>
