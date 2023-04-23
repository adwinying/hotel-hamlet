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

        <InputDropdown
          v-model="form.room_type_id"
          :disabled="isRoomTypeDropdownDisabled"
          :errors="form.errors.room_type_id"
          :options="roomTypeOptions"
          name="room_type_id"
          label="Room Type"
          class="lg:col-span-4" />

        <InputText
          v-model="form.room_no"
          :errors="form.errors.room_no"
          name="room_no"
          label="Room No"
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
import { computed, ComputedRef, PropType, toRef, watch } from 'vue'

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import useForm from '@/composables/useForm'
import DropdownOption from '@/types/DropdownOption'

type PageProps = App.Http.Responses.RoomFormResponse
const props = defineProps({
  room: {
    type: Object as PropType<PageProps['room']>,
    default: null,
  },

  hotels: {
    type: Array as PropType<PageProps['hotels']>,
    required: true,
  },

  roomTypes: {
    type: Array as PropType<PageProps['roomTypes']>,
    required: true,
  },
})

const initialFormData = {
  hotel_id: 0,
  room_type_id: 0,
  room_no: '',
}

const { form, isEditForm, onFormSubmit, onDeleteClick } = useForm(
  toRef(props, 'room'),
  initialFormData,
)

const pageTitle = computed(() => (isEditForm.value ? 'Edit Room' : 'New Room'))

const submitText = computed(() =>
  isEditForm.value ? 'Update Room' : 'Create Room',
)

watch(
  () => form.hotel_id,
  () => {
    form.room_type_id = 0
  },
)

const isRoomTypeDropdownDisabled = computed(() => form.hotel_id === 0)

const hotelOptions: ComputedRef<DropdownOption[]> = computed(() =>
  props.hotels.reduce(
    (acc, hotel) => [
      ...acc,
      {
        value: hotel.id,
        label: hotel.name,
      },
    ],
    [{ value: 0, label: '== Select ==' }],
  ),
)

const filteredRoomTypes = computed(() =>
  form.hotel_id
    ? props.roomTypes.filter((roomType) => roomType.hotel_id === form.hotel_id)
    : props.roomTypes,
)
const roomTypeOptions: ComputedRef<DropdownOption[]> = computed(() =>
  filteredRoomTypes.value.reduce(
    (acc, roomType) => [
      ...acc,
      {
        value: roomType.id.toString(),
        label: roomType.name,
      },
    ],
    [{ value: '', label: '' }],
  ),
)
</script>
