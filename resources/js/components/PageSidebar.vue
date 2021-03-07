<template>
  <div
    v-if="isActive"
    class="md:hidden fixed inset-0 z-10 bg-black bg-opacity-30"
    @click.prevent="onCloseClick" />
  <div
    v-bind="$attrs"
    class="w-72 h-screen bg-white border-r border-gray-200 transition-all">
    <div class="md:hidden absolute top-0 right-0 w-8 mx-4 my-8">
      <a
        href="#"
        @click.prevent="onCloseClick">
        <img src="/img/icons/cross.svg">
      </a>
    </div>

    <div class="flex items-center h-14 m-6 space-x-2">
      <img
        class="h-full"
        src="/img/logo.svg">
      <span class="text-2xl font-display">
        Admin
      </span>
    </div>

    <div class="w-full my-3 px-3">
      <page-sidebar-item
        v-for="(item, i) in items"
        v-bind="item"
        :key="i"
        :is-active="isActiveItem(item)" />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import SidebarItem from '../types/SidebarItem'
import PageSidebarItem from './PageSidebarItem.vue'

export default defineComponent({
  name: 'PageSidebar',

  components: {
    PageSidebarItem,
  },

  props: {
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
  },

  emits: [
    'hide',
  ],

  setup(props, { emit }) {
    const isActiveItem = (item: SidebarItem) => item.name === props.activeItem

    const hideSidebar = () => emit('hide')
    const onCloseClick = () => { hideSidebar() }

    return {
      isActiveItem,
      onCloseClick,
    }
  },
})
</script>
