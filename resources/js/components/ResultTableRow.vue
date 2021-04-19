<template>
  <tr class="hover:bg-gray-100 focus:bg-gray-100">
    <slot />
  </tr>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ResultTableRow',
})
</script>
