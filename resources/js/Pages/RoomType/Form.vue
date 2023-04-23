<template>
  <Page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <InputDropdown
          v-model="form.hotel_id"
          :errors="form.errors.hotel_id"
          :options="hotelOptions"
          name="hotel_id"
          label="Hotel"
          class="lg:col-span-4" />

        <InputText
          v-model="form.name"
          :errors="form.errors.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <InputText
          v-model="form.price"
          :errors="form.errors.price"
          name="price"
          label="Price"
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

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import useForm from '@/composables/useForm'
import DropdownOption from '@/types/DropdownOption'

type PageProps = App.Http.Responses.Admin.RoomTypeFormResponse
const props = defineProps({
  roomType: {
    type: Object as PropType<PageProps['roomType']>,
    default: null,
  },

  hotels: {
    type: Array as PropType<PageProps['hotels']>,
    required: true,
  },
})

const initialFormData = {
  hotel_id: 0,
  name: '',
  price: '',
}

const { form, isEditForm, onFormSubmit, onDeleteClick } = useForm(
  toRef(props, 'roomType'),
  initialFormData,
)

const pageTitle = computed(() =>
  isEditForm.value ? 'Edit Room Type' : 'New Room Type',
)

const submitText = computed(() =>
  isEditForm.value ? 'Update Room Type' : 'Create Room Type',
)

const hotelOptions = computed<DropdownOption[]>(() =>
  props.hotels.reduce(
    (acc, hotel) => [
      ...acc,
      {
        value: hotel.id.toString(),
        label: hotel.name,
      },
    ],
    [{ value: '', label: '' }],
  ),
)
</script>
