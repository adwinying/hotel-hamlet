<template>
  <div class="w-72 h-screen bg-white border-r border-gray-200">
    <div class="flex items-center h-14 m-6 space-x-2">
      <img
        class="h-full"
        src="/img/logo.svg">
      <span class="text-2xl font-display">
        Admin
      </span>
    </div>

    <div class="w-full my-3 px-3">
      <div
        v-for="(item, i) in items"
        :key="i">
        <inertia-link
          :href="item.url"
          :class="{ 'bg-gray-200': isActiveItem(item) }"
          class="flex items-center p-3 space-x-2 rounded hover:bg-cyan-100">
          <img
            :src="item.imgSrc"
            :alt="item.name"
            class="w-8">
          <span>
            {{ item.name }}
          </span>
        </inertia-link>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import SidebarItem from '../types/SidebarItem'

export default defineComponent({
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

  setup(props) {
    const isActiveItem = (item: SidebarItem) => item.name === props.activeItem

    return {
      isActiveItem,
    }
  },
})
</script>
