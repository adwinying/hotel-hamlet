<template>
  <page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <input-text
          v-model="form.check_in_date"
          :errors="form.errors.check_in_date"
          name="check_in_date"
          type="date"
          label="Check In Date"
          class="lg:col-span-4" />

        <input-text
          v-model="form.check_out_date"
          :errors="form.errors.check_out_date"
          name="check_out_date"
          type="date"
          label="Check Out Date"
          class="lg:col-span-4" />

        <input-dropdown
          v-model="form.hotel_id"
          :errors="form.errors.hotel_id"
          :options="hotelOptions"
          name="hotel_id"
          label="Hotel"
          class="lg:col-span-4" />

        <input-dropdown
          v-model="form.room_type_id"
          :disabled="isRoomTypeDropdownDisabled"
          :errors="form.errors.room_type_id"
          :options="roomTypeOptions"
          name="room_type_id"
          label="Room Type"
          class="lg:col-span-4" />

        <input-dropdown
          v-model="form.room_id"
          :disabled="isRoomDropdownDisabled"
          :errors="form.errors.room_id"
          :options="roomOptions"
          name="room_id"
          label="Room No."
          class="lg:col-span-4" />

        <input-text
          v-model="form.guest_name"
          :errors="form.errors.guest_name"
          name="guest_name"
          label="Guest Name"
          class="lg:col-span-4" />

        <input-text
          v-model="form.guest_email"
          :errors="form.errors.guest_email"
          name="guest_email"
          label="Guest Email"
          class="lg:col-span-4" />

        <input-textbox
          v-model="form.remarks"
          :errors="form.errors.remarks"
          name="remarks"
          label="Remarks"
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
            <trash-icon class="w-6" />
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
import {
  computed,
  defineComponent,
  PropType,
  ref,
  toRef,
  watch,
} from 'vue'
import { TrashIcon } from '@heroicons/vue/outline'

import DropdownOption from '@/types/DropdownOption'
import { fetchAvailableRooms } from '@/api/room'
import Hotel from '@/types/Models/Hotel'
import RoomType from '@/types/Models/RoomType'
import Room from '@/types/Models/Room'
import Reservation from '@/types/Models/Reservation'
import useForm from '@/composables/useForm'

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import InputTextbox from '@/components/InputTextbox.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'ReservationForm',

  components: {
    InputDropdown,
    InputText,
    InputTextbox,
    LoadingButton,
    TrashIcon,
  },

  props: {
    reservation: {
      type: Object as PropType<Reservation | null>,
      default: null,
    },

    hotels: {
      type: Array as PropType<Hotel[]>,
      required: true,
    },

    roomTypes: {
      type: Array as PropType<RoomType[]>,
      required: true,
    },
  },

  setup(props) {
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

    const {
      form,
      isEditForm,
      objectId,
      onFormSubmit,
      onDeleteClick,
    } = useForm(toRef(props, 'reservation'), initialFormData)

    const pageTitle = computed(() => (
      isEditForm.value ? 'Edit Reservation' : 'New Reservation'
    ))

    const submitText = computed(() => (
      isEditForm.value ? 'Update Reservation' : 'Create Reservation'
    ))

    // hotel options
    const hotelOptions = computed<DropdownOption[]>(
      () => props.hotels.reduce((acc, hotel) => [...acc, {
        value: hotel.id,
        label: hotel.name,
      }], [{ value: 0, label: '== Select ==' }]),
    )

    // room type options
    watch(() => form.hotel_id, () => {
      form.room_type_id = 0
      form.room_id = 0
    })
    const isRoomTypeDropdownDisabled = computed(() => form.hotel_id === 0)
    const filteredRoomTypes = computed<RoomType[]>(() => (
      form.hotel_id
        ? props.roomTypes
          .filter((roomType) => (roomType.hotel_id === form.hotel_id))
        : props.roomTypes
    ))
    const roomTypeOptions = computed<DropdownOption[]>(
      () => filteredRoomTypes.value.reduce((acc, roomType) => [...acc, {
        value: roomType.id.toString(),
        label: roomType.name,
      }], [{ value: '', label: '' }]),
    )

    // room options
    const availableRooms = ref<Room[]>([])
    const isLoadingAvailableRooms = ref(false)
    const updateAvailableRooms = () => {
      isLoadingAvailableRooms.value = true
      fetchAvailableRooms(
        form.room_type_id,
        form.check_in_date,
        form.check_out_date,
      ).then((rooms) => {
        availableRooms.value = rooms
        isLoadingAvailableRooms.value = false
      }).catch(
        () => window.location.reload,
      )
    }
    watch(() => form.room_type_id, () => { form.room_id = 0 })
    watch(
      () => [form.room_type_id, form.check_in_date, form.check_out_date],
      () => {
        if (form.check_in_date !== ''
          && form.check_out_date !== ''
          && form.room_type_id !== 0) updateAvailableRooms()
      },
      { immediate: true },
    )
    const isRoomDropdownDisabled = computed(() => (
      isRoomTypeDropdownDisabled.value
        || form.check_in_date === ''
        || form.check_out_date === ''
        || form.room_type_id === 0
    ))
    const roomOptions = computed<DropdownOption[]>(
      () => availableRooms.value.reduce((acc, room) => [...acc, {
        value: room.id.toString(),
        label: room.room_no,
      }], [{ value: '', label: '' }]),
    )

    return {
      pageTitle,
      submitText,
      isEditForm,
      hotelOptions,
      roomTypeOptions,
      roomOptions,
      form,
      isRoomTypeDropdownDisabled,
      isRoomDropdownDisabled,
      onFormSubmit,
      onDeleteClick,
      objectId,
    }
  },
})
</script>
