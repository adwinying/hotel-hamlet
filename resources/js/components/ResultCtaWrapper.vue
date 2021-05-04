<template>
  <div class="flex justify-end mt-6 mb-3">
    <slot />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ResultCtaWrapper',
})
</script>
