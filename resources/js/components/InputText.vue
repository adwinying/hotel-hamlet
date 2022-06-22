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
      :type="type"
      :name="name"
      :class="errors ? 'border-red-500' : 'border-gray-300'"
      class="mt-1 block w-full rounded-md shadow-sm focus:border-cyan-600 focus:ring-cyan-500 disabled:bg-gray-200 sm:text-sm" />

    <div
      v-if="errors"
      class="mt-1 text-sm text-red-500">
      {{ errors }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { useVModel } from '@vueuse/core'
import InputData from '@/types/InputData'

const props = defineProps({
  modelValue: {
    type: [String, Number, Boolean] as PropType<InputData>,
    default: '',
  },

  label: {
    type: String,
    required: true,
  },

  name: {
    type: String,
    required: true,
  },

  type: {
    type: String,
    default: 'text',
  },

  errors: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['update:modelValue'])

const value = useVModel(props, 'modelValue', emit)
</script>
