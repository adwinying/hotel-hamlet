<template>
  <div class="fixed w-full h-14 bg-white shadow-md">
    <div class="absolute top-0 left-0 bottom-0 w-14 p-3">
      <a href="#">
        <menu-icon
          class="h-full"
          @click.prevent="onMenuClick" />
      </a>
    </div>

    <div class="flex h-full justify-center items-center space-x-2">
      <img
        class="w-8 h-full py-3"
        src="@/../static/img/logo.svg">
      <span class="text-2xl font-display">
        Admin
      </span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { MenuIcon } from '@heroicons/vue/outline'

export default defineComponent({
  name: 'PageNavbar',

  components: {
    MenuIcon,
  },

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
