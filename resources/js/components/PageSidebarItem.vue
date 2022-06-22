<template>
  <div>
    <InertiaLink
      :href="url"
      :class="{ 'bg-gray-200': isActive, 'text-gray-500': !isActive }"
      class="flex items-center space-x-2 rounded p-3 font-bold hover:bg-cyan-100">
      <component
        :is="iconMap[icon]"
        class="w-8"
        :class="isActive ? 'text-gray-700' : 'text-gray-500'" />
      <span>
        {{ name }}
      </span>
    </InertiaLink>
  </div>
</template>

<script setup lang="ts">
import { RenderFunction } from 'vue'
import {
  HomeIcon,
  OfficeBuildingIcon,
  ColorSwatchIcon,
  KeyIcon,
  BookOpenIcon,
  UserCircleIcon,
} from '@heroicons/vue/outline'

defineProps({
  isActive: {
    type: Boolean,
    default: false,
  },

  url: {
    type: String,
    required: true,
  },

  icon: {
    type: String,
    required: true,
  },

  name: {
    type: String,
    required: true,
  },
})

const iconMap: Record<string, RenderFunction> = {
  HomeIcon,
  OfficeBuildingIcon,
  ColorSwatchIcon,
  KeyIcon,
  BookOpenIcon,
  UserCircleIcon,
}
</script>
