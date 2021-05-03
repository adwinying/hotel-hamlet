<template>
  <page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <input-text
          v-model="form.name"
          :errors="form.errors.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <input-checkbox
          v-model="form.is_hidden"
          :errors="form.errors.is_hidden"
          name="is_hidden"
          label="Is Hidden?"
          class="lg:col-span-4" />
      </div>

      <hr class="my-6 border-t">

      <div class="flex justify-between">
        <div>
          <loading-button
            v-if="isEditForm"
            variant="danger"
            :is-loading="form.processing"
            @click.prevent="onDeleteClick">
            <inline-svg
              class="w-6"
              src="/img/icons/trash.svg" />
          </loading-button>
        </div>

        <div>
          <loading-button
            type="submit"
            :is-loading="form.processing">
            {{ submitText }}
          </loading-button>
        </div>
      </div>
    </form>
  </page>
</template>

<script lang="ts">
import { computed, defineComponent, PropType } from 'vue'
import InlineSvg from 'vue-inline-svg'
import Hotel from '@/types/Models/Hotel'
import useForm from '@/composables/useForm'

import InputText from '@/components/InputText.vue'
import InputCheckbox from '@/components/InputCheckbox.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'HotelForm',

  components: {
    InlineSvg,
    InputText,
    InputCheckbox,
    LoadingButton,
  },

  props: {
    hotel: {
      type: Object as PropType<Hotel | null>,
      default: null,
    },
  },

  setup(props) {
    const initialFormData = {
      name: '',
      is_hidden: false,
    }

    const {
      form,
      isEditForm,
      onFormSubmit,
      onDeleteClick,
    } = useForm(props.hotel, initialFormData)

    const pageTitle = computed(() => (
      isEditForm.value ? 'Edit Hotel' : 'New Hotel'
    ))

    const submitText = computed(() => (
      isEditForm.value ? 'Update Hotel' : 'Create Hotel'
    ))

    return {
      pageTitle,
      submitText,
      isEditForm,
      form,
      onFormSubmit,
      onDeleteClick,
    }
  },
})
</script>
