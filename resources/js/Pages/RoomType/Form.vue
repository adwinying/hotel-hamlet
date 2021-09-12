<template>
  <page :title="pageTitle">
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

      <hr class="my-6 border-t">

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
  </page>
</template>

<script setup lang="ts">
import { computed, PropType, toRef } from 'vue'
import { TrashIcon } from '@heroicons/vue/outline'

import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import RoomType from '@/types/Models/RoomType'
import useForm from '@/composables/useForm'

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'

const props = defineProps({
  roomType: {
    type: Object as PropType<RoomType | null>,
    default: null,
  },

  hotels: {
    type: Array as PropType<Hotel[]>,
    required: true,
  },
})

const initialFormData = {
  hotel_id: 0,
  name: '',
  price: '',
}

const {
  form,
  isEditForm,
  onFormSubmit,
  onDeleteClick,
} = useForm(toRef(props, 'roomType'), initialFormData)

const pageTitle = computed(() => (
  isEditForm.value ? 'Edit Room Type' : 'New Room Type'
))

const submitText = computed(() => (
  isEditForm.value ? 'Update Room Type' : 'Create Room Type'
))

const hotelOptions = computed<DropdownOption[]>(
  () => props.hotels.reduce((acc, hotel) => [...acc, {
    value: hotel.id.toString(),
    label: hotel.name,
  }], [{ value: '', label: '' }]),
)
</script>
