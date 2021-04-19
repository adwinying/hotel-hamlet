<template>
  <div class="bg-white border rounded-md shadow overflow-x-auto">
    <table class="w-full">
      <slot />
    </table>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ResultTableWrapper',
})
</script>
