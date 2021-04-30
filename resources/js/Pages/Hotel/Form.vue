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

      <div class="flex justify-end">
        <loading-button
          type="submit"
          :is-loading="form.processing">
          {{ submitText }}
        </loading-button>
      </div>
    </form>
  </page>
</template>

<script lang="ts">
import { computed, defineComponent, PropType } from 'vue'
import Hotel from '@/types/Models/Hotel'
import { showToast } from '@/composables/useAlert'
import useForm from '@/composables/useForm'

import InputText from '@/components/InputText.vue'
import InputCheckbox from '@/components/InputCheckbox.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'HotelForm',

  components: {
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
      objectId: hotelId,
      isEditForm,
    } = useForm(props.hotel, initialFormData)

    const pageTitle = computed(() => (
      isEditForm.value ? 'Edit Hotel' : 'New Hotel'
    ))

    const submitText = computed(() => (
      isEditForm.value ? 'Update Hotel' : 'Create Hotel'
    ))

    const onFormSubmit = () => {
      form.clearErrors()
      form.put(`/admin/hotels/${hotelId.value}`, {
        onSuccess: () => {
          showToast('Hotel successfully updated', 'success')
        },
      })
    }

    return {
      pageTitle,
      submitText,
      form,
      onFormSubmit,
    }
  },
})
</script>
