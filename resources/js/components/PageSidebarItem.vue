<template>
  <div>
    <inertia-link
      :href="url"
      :class="{ 'bg-gray-200': isActive }"
      class="flex items-center p-3 space-x-2 rounded font-bold hover:bg-cyan-100">
      <img
        :src="imgSrc"
        :alt="name"
        class="w-8">
      <span>
        {{ name }}
      </span>
    </inertia-link>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageSidebarItem',

  props: {
    isActive: {
      type: Boolean,
      default: false,
    },

    url: {
      type: String,
      required: true,
    },

    imgSrc: {
      type: String,
      required: true,
    },

    name: {
      type: String,
      required: true,
    },
  },
})
</script>
