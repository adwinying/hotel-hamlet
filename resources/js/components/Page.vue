<template>
  <div class="bg-gray-50">
    <PageSidebar
      :items="sidebarItems"
      :is-active="isSidebarActive"
      :active-item="activeSidebarItem"
      :user-name="userName"
      class="fixed top-0 z-50 md:left-0"
      :class="isSidebarActive ? 'left-0' : '-left-72'"
      @hide="hideSidebar" />

    <div class="min-h-screen md:pl-72">
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

<script setup lang="ts">
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SidebarItem from '@/types/SidebarItem'

import PageSidebar from '@/components/PageSidebar.vue'
import PageNavbar from '@/components/PageNavbar.vue'
import PageHeader from '@/components/PageHeader.vue'
import PageContent from '@/components/PageContent.vue'
import PageCard from '@/components/PageCard.vue'

interface CommonPageProps extends Record<string, unknown> {
  sidebarItems?: SidebarItem[]
  userInfo?: {
    name: string
  }
}

defineProps({
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
})

const { props, url } = usePage<CommonPageProps>()

const sidebarItems = computed(() => props.sidebarItems ?? [])

const activeSidebarItem = computed(() => {
  if (/^\/admin\/hotels.*$/.test(url)) return 'Hotels'
  if (/^\/admin\/rooms.*$/.test(url)) return 'Rooms'
  if (/^\/admin\/room_types.*$/.test(url)) return 'Room Types'
  if (/^\/admin\/reservations.*$/.test(url)) return 'Reservations'
  if (/^\/admin\/profile$/.test(url)) return 'Profile'
  if (/^\/admin$/.test(url)) return 'Dashboard'

  return undefined
})

const isSidebarActive = ref(false)
const showSidebar = () => {
  isSidebarActive.value = true
}
const hideSidebar = () => {
  isSidebarActive.value = false
}

const userName = computed(() => props.userInfo?.name)
</script>
