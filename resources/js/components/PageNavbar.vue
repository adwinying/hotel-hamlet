<template>
  <div class="fixed w-full h-14 bg-white shadow-md">
    <div class="absolute top-0 left-0 bottom-0 w-14 p-3">
      <a href="#">
        <MenuIcon
          class="h-full"
          @click.prevent="onMenuClick" />
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

<script setup lang="ts">
import { MenuIcon } from '@heroicons/vue/outline'

const emit = defineEmits([
  'showSidebar',
])

const onMenuClick = () => emit('showSidebar')
</script>
