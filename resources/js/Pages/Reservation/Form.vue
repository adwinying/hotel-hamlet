<template>
  <Page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <InputText
          v-model="form.check_in_date"
          :errors="form.errors.check_in_date"
          name="check_in_date"
          type="date"
          label="Check In Date"
          class="lg:col-span-4" />

        <InputText
          v-model="form.check_out_date"
          :errors="form.errors.check_out_date"
          name="check_out_date"
          type="date"
          label="Check Out Date"
          class="lg:col-span-4" />

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

        <InputDropdown
          v-model="form.room_id"
          :disabled="isRoomDropdownDisabled"
          :errors="form.errors.room_id"
          :options="roomOptions"
          name="room_id"
          label="Room No."
          class="lg:col-span-4" />

        <InputText
          v-model="form.guest_name"
          :errors="form.errors.guest_name"
          name="guest_name"
          label="Guest Name"
          class="lg:col-span-4" />

        <InputText
          v-model="form.guest_email"
          :errors="form.errors.guest_email"
          name="guest_email"
          label="Guest Email"
          class="lg:col-span-4" />

        <InputTextbox
          v-model="form.remarks"
          :errors="form.errors.remarks"
          name="remarks"
          label="Remarks"
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
import { computed, PropType, ref, toRef, watch } from 'vue'

import { fetchAvailableRooms } from '@/api/room'
import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import InputTextbox from '@/components/InputTextbox.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import useForm from '@/composables/useForm'
import DropdownOption from '@/types/DropdownOption'
import Room from '@/types/Models/Room'

type PageProps = App.Http.Responses.Admin.ReservationFormResponse
const props = defineProps({
  reservation: {
    type: Object as PropType<PageProps['reservation']>,
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
  check_in_date: '',
  check_out_date: '',
  hotel_id: 0,
  room_type_id: 0,
  room_id: 0,
  guest_name: '',
  guest_email: '',
  remarks: '',
}

const { form, isEditForm, onFormSubmit, onDeleteClick } = useForm(
  toRef(props, 'reservation'),
  initialFormData,
)

const pageTitle = computed(() =>
  isEditForm.value ? 'Edit Reservation' : 'New Reservation',
)

const submitText = computed(() =>
  isEditForm.value ? 'Update Reservation' : 'Create Reservation',
)

// hotel options
const hotelOptions = computed<DropdownOption[]>(() =>
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

// room type options
watch(
  () => form.hotel_id,
  () => {
    form.room_type_id = 0
    form.room_id = 0
  },
)
const isRoomTypeDropdownDisabled = computed(() => form.hotel_id === 0)
const filteredRoomTypes = computed(() =>
  form.hotel_id
    ? props.roomTypes.filter((roomType) => roomType.hotel_id === form.hotel_id)
    : props.roomTypes,
)
const roomTypeOptions = computed<DropdownOption[]>(() =>
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

// room options
const availableRooms = ref<Room[]>([])
const isLoadingAvailableRooms = ref(false)
const updateAvailableRooms = () => {
  isLoadingAvailableRooms.value = true
  fetchAvailableRooms({
    room_type_id: form.room_type_id,
    check_in_date: form.check_in_date,
    check_out_date: form.check_out_date,
    reservation_id: props.reservation?.id ?? null,
  })
    .then((rooms) => {
      availableRooms.value = rooms
      isLoadingAvailableRooms.value = false
    })
    .catch(() => {
      window.location.reload()
    })
}
watch(
  () => form.room_type_id,
  () => {
    form.room_id = 0
  },
)
watch(
  () => [form.room_type_id, form.check_in_date, form.check_out_date],
  () => {
    if (
      form.check_in_date !== '' &&
      form.check_out_date !== '' &&
      form.room_type_id !== 0
    )
      updateAvailableRooms()
  },
  { immediate: true },
)
const isRoomDropdownDisabled = computed(
  () =>
    isRoomTypeDropdownDisabled.value ||
    form.check_in_date === '' ||
    form.check_out_date === '' ||
    form.room_type_id === 0,
)
const roomOptions = computed<DropdownOption[]>(() =>
  availableRooms.value.reduce(
    (acc, room) => [
      ...acc,
      {
        value: room.id.toString(),
        label: room.room_no,
      },
    ],
    [{ value: '', label: '' }],
  ),
)
</script>
