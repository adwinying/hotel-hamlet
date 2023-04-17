<template>
  <td :class="{ 'border-t': border }">
    <Link
      :href="href"
      class="flex items-center p-4">
      <slot />
    </Link>
  </td>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps({
  href: {
    type: String,
    default: '#',
  },

  border: {
    type: Boolean,
    default: true,
  },
})
</script>
