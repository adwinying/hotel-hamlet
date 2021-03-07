<template>
  <div>
    <inertia-link
      :href="url"
      :class="{ 'bg-gray-200': isActive, 'text-gray-500': !isActive }"
      class="flex items-center p-3 space-x-2 rounded font-bold hover:bg-cyan-100">
      <inline-svg
        class="w-8"
        :class="isActive ? 'text-gray-700' : 'text-gray-500'"
        :src="imgSrc" />
      <span>
        {{ name }}
      </span>
    </inertia-link>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import InlineSvg from 'vue-inline-svg'

export default defineComponent({
  name: 'PageSidebarItem',

  components: {
    InlineSvg,
  },

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
