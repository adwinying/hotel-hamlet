<template>
  <th class="p-4 text-left font-bold">
    <slot />
  </th>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ResultTableHeader',
})
</script>
