<template>
  <component
    :is="as"
    :disabled="isLoading"
    :class="`flex items-center px-6 py-2 rounded-md
      bg-${color}-600 hover:bg-${color}-800 text-gray-50 font-semibold
      disabled:bg-${color}-300 disabled:cursor-auto
      focus:outline-none focus:ring-4 focus:ring-${color}-500 focus:ring-opacity-60`">
    <div
      v-if="isLoading"
      class="btn-spinner mr-2" />
    <slot />
  </component>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'

export default defineComponent({
  props: {
    as: {
      type: String,
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
  },

  setup(props) {
    const color = computed(() => (props.variant === 'primary' ? 'cyan' : 'red'))

    return {
      color,
    }
  },
})
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
