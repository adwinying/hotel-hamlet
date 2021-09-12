<template>
  <div class="col-span-6">
    <label
      :for="name"
      class="block text-sm font-semibold text-gray-700">
      {{ label }}
    </label>

    <select
      :id="name"
      v-model="value"
      v-bind="$attrs"
      :name="name"
      :class="errors ? 'border-red-500' : 'border-gray-300'"
      class="block w-full mt-1 py-2 px-3 border border-gray-300
      bg-white rounded-md shadow-sm disabled:bg-gray-200
      focus:outline-none focus:ring-cyan-500 focus:border-cyan-600
      sm:text-sm">
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value">
        {{ option.label || option.value }}
      </option>
    </select>

    <div
      v-if="errors"
      class="mt-1 text-red-500 text-sm">
      {{ errors }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { useVModel } from '@vueuse/core'
import InputData from '@/types/InputData'
import DropdownOption from '@/types/DropdownOption'

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

  options: {
    type: Object as PropType<DropdownOption[]>,
    required: true,
  },

  errors: {
    type: String,
    default: null,
  },
})

const emit = defineEmits([
  'update:modelValue',
])

const value = useVModel(props, 'modelValue', emit)
</script>
