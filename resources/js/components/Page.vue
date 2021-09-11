<template>
  <div class="bg-gray-50">
    <PageSidebar
      :items="sidebarItems"
      :is-active="isSidebarActive"
      :active-item="activeSidebarItem"
      :user-name="userName"
      class="fixed top-0 md:left-0 z-50"
      :class="isSidebarActive ? 'left-0' : '-left-72'"
      @hide="hideSidebar" />

    <div class="md:pl-72 min-h-screen">
      <PageNavbar
        class="md:hidden"
        @show-sidebar="showSidebar" />

      <PageHeader v-if="header">
        {{ title }}

        <template #pre-title>
          <slot name="pre-title" />
        </template>

        <template #post-title>
          <slot name="post-title" />
        </template>
      </PageHeader>

      <PageContent>
        <PageCard v-if="card">
          <slot />
        </PageCard>
        <template v-else>
          <slot />
        </template>
      </PageContent>
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
import SidebarItem from '@/types/SidebarItem'

import PageSidebar from '@/components/PageSidebar.vue'
import PageNavbar from '@/components/PageNavbar.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageContent from '@/components/PageContent.vue'
import PageCard from '@/components/PageCard.vue'

interface CommonPageProps {
  sidebarItems?: SidebarItem[];
  userInfo?: {
    name: string;
  };
}
export default defineComponent({
  name: 'Page',

  components: {
    PageSidebar,
    PageNavbar,
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
    const { props, url } = usePage()
    const pageProps: ComputedRef<CommonPageProps> = props

    const sidebarItems = computed(
      () => pageProps.value.sidebarItems ?? [],
    )

    const activeSidebarItem = computed(() => {
      const path = url.value

      if (/^\/admin\/hotels.*$/.test(path)) return 'Hotels'
      if (/^\/admin\/rooms.*$/.test(path)) return 'Rooms'
      if (/^\/admin\/reservations.*$/.test(path)) return 'Reservations'
      if (/^\/admin\/profile$/.test(path)) return 'Profile'
      if (/^\/admin$/.test(path)) return 'Dashboard'

      return undefined
    })

    const isSidebarActive = ref(false)
    const showSidebar = () => { isSidebarActive.value = true }
    const hideSidebar = () => { isSidebarActive.value = false }

    const userName = computed(() => pageProps.value.userInfo?.name)

    return {
      sidebarItems,
      activeSidebarItem,
      isSidebarActive,
      showSidebar,
      hideSidebar,
      userName,
    }
  },
})
</script>
