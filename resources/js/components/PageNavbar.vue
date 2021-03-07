<template>
  <div class="fixed w-full h-14 bg-white shadow-md">
    <div class="absolute top-0 left-0 bottom-0 w-14 p-3">
      <a href="#">
        <img
          class="h-full"
          alt="Show Sidebar"
          src="/img/icons/menu.svg"
          @click.prevent="onMenuClick">
      </a>
    </div>

    <div class="flex h-full justify-center items-center space-x-2">
      <img
        class="w-8 h-full py-3"
        src="/img/logo.svg">
      <span class="text-2xl font-display">
        Admin
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageNavbar',

  emits: [
    'showSidebar',
  ],

  setup(_, { emit }) {
    const onMenuClick = () => emit('showSidebar')

    return {
      onMenuClick,
    }
  },
})
</script>
