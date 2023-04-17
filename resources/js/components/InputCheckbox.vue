<template>
  <div class="col-span-6">
    <label
      :for="name"
      class="block text-sm font-semibold text-gray-700">
      {{ label }}
    </label>

    <input
      :id="name"
      v-model="value"
      v-bind="$attrs"
      :name="name"
      type="checkbox"
      :class="errors ? 'border-red-500' : 'border-gray-300'"
      class="rounded border text-cyan-600 focus:border-cyan-600 focus:outline-none focus:ring-cyan-500 sm:text-sm" />

    <div
      v-if="errors"
      class="mt-1 text-sm text-red-500">
      {{ errors }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { useVModel } from '@vueuse/core'
import { PropType } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number, Boolean] as PropType<boolean>,
    default: false,
  },

  label: {
    type: String,
    required: true,
  },

  name: {
    type: String,
    required: true,
  },

  errors: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['update:modelValue'])

const value = useVModel(props, 'modelValue', emit)
</script>
