<template>
  <td :class="{ 'border-t': border }">
    <InertiaLink
      :href="href"
      class="flex items-center p-4">
      <slot />
    </InertiaLink>
  </td>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'ResultTableCell',

  props: {
    href: {
      type: String,
      default: '#',
    },

    border: {
      type: Boolean,
      default: true,
    },
  },
})
</script>
