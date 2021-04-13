<template>
  <page title="Hotels">
    hello from hotels
  </page>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import InputText from '../../components/InputText.vue'
import LoadingButton from '../../components/LoadingButton.vue'

export default defineComponent({
  name: 'ProfileIndex',

  components: {
    InputText,
    LoadingButton,
  },

  setup(props) {
  },
})
</script>
