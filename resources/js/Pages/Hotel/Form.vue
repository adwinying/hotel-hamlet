<template>
  <Page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <InputText
          v-model="form.name"
          :errors="form.errors.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <InputCheckbox
          v-model="form.is_hidden"
          :errors="form.errors.is_hidden"
          name="is_hidden"
          label="Is Hidden?"
          class="lg:col-span-4" />
      </div>

      <hr class="my-6 border-t" />

      <div class="flex justify-between">
        <div>
          <LoadingButton
            v-if="isEditForm"
            variant="danger"
            :is-loading="form.processing"
            @click.prevent="onDeleteClick">
            <TrashIcon class="w-6" />
          </LoadingButton>
        </div>

        <div>
          <LoadingButton
            type="submit"
            :is-loading="form.processing">
            {{ submitText }}
          </LoadingButton>
        </div>
      </div>
    </form>
  </Page>
</template>

<script setup lang="ts">
import { TrashIcon } from '@heroicons/vue/outline'
import { computed, PropType, toRef } from 'vue'

import InputCheckbox from '@/components/InputCheckbox.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import useForm from '@/composables/useForm'

type PageProps = App.Http.Responses.Admin.HotelFormResponse
const props = defineProps({
  hotel: {
    type: Object as PropType<PageProps['hotel']>,
    default: null,
  },
})

const initialFormData = {
  name: '',
  is_hidden: false,
}

const { form, isEditForm, onFormSubmit, onDeleteClick } = useForm(
  toRef(props, 'hotel'),
  initialFormData,
)

const pageTitle = computed(() =>
  isEditForm.value ? 'Edit Hotel' : 'New Hotel',
)

const submitText = computed(() =>
  isEditForm.value ? 'Update Hotel' : 'Create Hotel',
)
</script>
