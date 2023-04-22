<template>
  <component
    :is="as"
    :disabled="isLoading"
    :class="`flex items-center rounded-md px-6 py-2
      bg-${color}-600 hover:bg-${color}-800 font-semibold text-gray-50
      disabled:bg-${color}-300 focus:outline-none
      focus:ring-4 disabled:cursor-auto focus:ring-${color}-500 focus:ring-opacity-60`">
    <div
      v-if="isLoading"
      class="btn-spinner mr-2" />
    <slot />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  as: {
    type: [Object, String],
    default: 'button',
  },

  isLoading: {
    type: Boolean,
    default: false,
  },

  variant: {
    type: String,
    default: 'primary',
  },
})

const color = computed(() => (props.variant === 'primary' ? 'cyan' : 'red'))
</script>

<style scoped>
.btn-spinner,
.btn-spinner:after {
  border-radius: 50%;
  width: 1.5em;
  height: 1.5em;
}

.btn-spinner {
  font-size: 10px;
  position: relative;
  text-indent: -9999em;
  border-top: 0.2em solid white;
  border-right: 0.2em solid white;
  border-bottom: 0.2em solid white;
  border-left: 0.2em solid transparent;
  transform: translateZ(0);
  animation: spinning 1s infinite linear;
}

@keyframes spinning {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
