<template>
  <div>
    <inertia-link
      :href="url"
      :class="{ 'bg-gray-200': isActive, 'text-gray-500': !isActive }"
      class="flex items-center p-3 space-x-2 rounded font-bold hover:bg-cyan-100">
      <component
        :is="icon"
        class="w-8"
        :class="isActive ? 'text-gray-700' : 'text-gray-500'" />
      <span>
        {{ name }}
      </span>
    </inertia-link>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import {
  HomeIcon,
  OfficeBuildingIcon,
  ColorSwatchIcon,
  KeyIcon,
  BookOpenIcon,
  UserCircleIcon,
} from '@heroicons/vue/outline'

export default defineComponent({
  name: 'PageSidebarItem',

  components: {
    HomeIcon,
    OfficeBuildingIcon,
    ColorSwatchIcon,
    KeyIcon,
    BookOpenIcon,
    UserCircleIcon,
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

    icon: {
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
