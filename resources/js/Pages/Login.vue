<template>
  <div class="text-xl">
    {{ greeting }} from Login
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'Login',

  setup() {
    const greeting = 'Hello'

    return {
      greeting,
    }
  },
})
</script>
