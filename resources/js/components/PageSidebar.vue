<template>
  <div
    v-if="isActive"
    class="fixed inset-0 z-10 bg-black bg-opacity-30 md:hidden"
    @click.prevent="onCloseClick" />
  <div
    v-bind="$attrs"
    class="h-screen w-72 border-r border-gray-200 bg-white transition-all">
    <div class="absolute top-0 right-0 mx-4 my-8 w-8 md:hidden">
      <a
        href="#"
        @click.prevent="onCloseClick">
        <x-icon />
      </a>
    </div>

    <div class="mx-3 my-6 flex h-14 items-center space-x-2">
      <img
        class="h-full"
        src="/img/logo.svg" />
      <span class="font-display text-2xl"> Admin </span>
    </div>

    <div class="my-3 w-full px-3">
      <PageSidebarItem
        v-for="(item, i) in items"
        v-bind="item"
        :key="i"
        :is-active="isActiveItem(item.name)" />

      <hr class="m-3" />

      <PageSidebarItem
        :name="userName"
        icon="UserCircleIcon"
        url="/admin/profile"
        :is-active="isActiveItem('Profile')" />

      <div>
        <form
          method="POST"
          action="/admin/logout">
          <button
            type="submit"
            class="flex w-full items-center space-x-2 rounded p-3 font-bold text-gray-500 hover:bg-cyan-100">
            <LogoutIcon class="w-8" />
            <span>Logout</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { LogoutIcon, XIcon } from '@heroicons/vue/outline'
import { PropType } from 'vue'

import PageSidebarItem from '@/components/PageSidebarItem.vue'
import SidebarItem from '@/types/SidebarItem'

const props = defineProps({
  isActive: {
    type: Boolean,
    required: true,
  },

  items: {
    type: Array as PropType<SidebarItem[]>,
    required: true,
  },

  activeItem: {
    type: String,
    default: null,
  },

  userName: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['hide'])

const isActiveItem = (itemName: string) => itemName === props.activeItem

const hideSidebar = () => emit('hide')
const onCloseClick = () => {
  hideSidebar()
}
</script>
