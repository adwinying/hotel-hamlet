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
      class="border rounded text-cyan-600
      focus:outline-none focus:ring-cyan-500 focus:border-cyan-600
      sm:text-sm">

    <div
      v-if="errors"
      class="mt-1 text-red-500 text-sm">
      {{ errors }}
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import { useVModel } from '@vueuse/core'
import InputData from '@/types/InputData'

export default defineComponent({
  name: 'InputCheckbox',

  props: {
    modelValue: {
      type: [String, Number, Boolean] as PropType<InputData>,
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
  },

  emits: [
    'update:modelValue',
  ],

  setup(props, { emit }) {
    const value = useVModel(props, 'modelValue', emit)

    return {
      value,
    }
  },
})
</script>
