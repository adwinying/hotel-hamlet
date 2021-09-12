<template>
  <td :class="{ 'border-t': border }">
    <InertiaLink
      :href="href"
      class="flex items-center p-4">
      <slot />
    </InertiaLink>
  </td>
</template>

<script setup lang="ts">
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
