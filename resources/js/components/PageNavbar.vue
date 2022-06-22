<template>
  <div class="fixed h-14 w-full bg-white shadow-md">
    <div class="absolute top-0 left-0 bottom-0 w-14 p-3">
      <a href="#">
        <MenuIcon
          class="h-full"
          @click.prevent="onMenuClick" />
      </a>
    </div>

    <div class="flex h-full items-center justify-center space-x-2">
      <img
        class="h-full w-8 py-3"
        src="/img/logo.svg" />
      <span class="font-display text-2xl"> Admin </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { MenuIcon } from '@heroicons/vue/outline'

const emit = defineEmits(['showSidebar'])

const onMenuClick = () => emit('showSidebar')
</script>
