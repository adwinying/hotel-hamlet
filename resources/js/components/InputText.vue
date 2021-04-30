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
      class="block w-full mt-1 rounded-md shadow-sm
        focus:ring-cyan-500 focus:border-cyan-600 disabled:bg-gray-200
        sm:text-sm">

    <div
      v-if="errors"
      class="mt-1 text-red-500 text-sm">
      {{ errors }}
    </div>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, PropType } from 'vue'
import InputData from '@/types/InputData'

export default defineComponent({
  name: 'InputText',

  props: {
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
  },

  emits: [
    'update:modelValue',
  ],

  setup(props, { emit }) {
    const value = computed({
      get: (): InputData => props.modelValue,
      set: (val: InputData) => emit('update:modelValue', val),
    })

    return {
      value,
    }
  },
})
</script>
