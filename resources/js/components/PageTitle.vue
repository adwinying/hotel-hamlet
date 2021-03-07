<template>
  <header class="w-full pt-12 pb-32 bg-cyan-500">
    <div class="container mx-auto px-10">
      <h1 class="font-bold text-5xl">
        <slot />
      </h1>
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageTitle',
})
</script>
