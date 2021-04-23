<template>
  <page title="Hotel Form">
    hello from hotel form
  </page>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'HotelForm',

  props: {
    hotel: {
      type: Object,
      required: true,
    },
  },

  setup() {
  },
})
</script>
