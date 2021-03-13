<template>
  <div class="w-full p-4 md:p-6 bg-white rounded shadow-lg">
    <slot />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageCard',
})
</script>
