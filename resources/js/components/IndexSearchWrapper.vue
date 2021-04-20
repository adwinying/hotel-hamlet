<template>
  <div class="flex space-x-3 mb-6">
    <slot />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'IndexSearchWrapper',
})
</script>
