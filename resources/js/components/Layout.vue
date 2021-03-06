<template>
  <div class="bg-gray-50">
    <Sidebar
      :items="sidebarItems"
      :is-active="isActive"
      :active-item="activeItem"
      class="fixed top-0 left-0 z-50" />

    <div class="pl-72 min-h-screen">
      <slot />
    </div>
  </div>
</template>

<script lang="ts">
import {
  computed,
  ComputedRef,
  defineComponent,
  ref,
} from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import SidebarItem from '../types/SidebarItem'
import Sidebar from './Sidebar.vue'

interface CommonPageProps {
  sidebarItems?: SidebarItem[];
}
export default defineComponent({
  components: {
    Sidebar,
  },

  setup() {
    const commonPageProps: ComputedRef<CommonPageProps> = computed(
      () => usePage().props.value,
    )

    const sidebarItems = computed(
      () => commonPageProps.value.sidebarItems ?? [],
    )

    const activeItem = computed(() => {
      const path = window.location.pathname

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
