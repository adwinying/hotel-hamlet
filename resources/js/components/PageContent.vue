<template>
  <main class="container mx-auto -mt-24 px-4 md:px-10">
    <slot />
  </main>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageContent',
})
</script>
