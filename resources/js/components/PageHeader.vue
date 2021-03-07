<template>
  <header class="w-full pt-20 md:pt-12 pb-32 bg-cyan-500">
    <div class="container mx-auto px-4 md:px-10 space-y-4">
      <slot name="pre-title" />

      <h1 class="font-bold text-5xl">
        <slot />
      </h1>

      <slot name="post-title" />
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageHeader',
})
</script>
