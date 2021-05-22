<template>
  <page :title="pageTitle">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <input-dropdown
          v-model="form.hotel_id"
          :errors="form.errors.hotel_id"
          :options="hotelOptions"
          name="hotel_id"
          label="Hotel"
          class="lg:col-span-4" />

        <input-dropdown
          v-model="form.room_type_id"
          :errors="form.errors.room_type_id"
          :options="roomTypeOptions"
          name="room_type_id"
          label="Room Type"
          class="lg:col-span-4" />

        <input-text
          v-model="form.room_no"
          :errors="form.errors.room_no"
          name="room_no"
          label="Room No"
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
  ComputedRef,
  defineComponent,
  PropType,
  toRef,
  watch,
} from 'vue'
import { TrashIcon } from '@heroicons/vue/outline'

import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import RoomType from '@/types/Models/RoomType'
import Room from '@/types/Models/Room'
import useForm from '@/composables/useForm'

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'RoomForm',

  components: {
    InputDropdown,
    InputText,
    LoadingButton,
    TrashIcon,
  },

  props: {
    room: {
      type: Object as PropType<Room | null>,
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
      hotel_id: 0,
      room_type_id: 0,
      room_no: '',
    }

    const {
      form,
      isEditForm,
      objectId,
      onFormSubmit,
      onDeleteClick,
    } = useForm(toRef(props, 'room'), initialFormData)

    const pageTitle = computed(() => (
      isEditForm.value ? 'Edit Room' : 'New Room'
    ))

    const submitText = computed(() => (
      isEditForm.value ? 'Update Room' : 'Create Room'
    ))

    watch(
      () => form.hotel_id,
      () => { form.room_type_id = 0 },
    )

    const hotelOptions: ComputedRef<DropdownOption[]> = computed(
      () => props.hotels.reduce((acc, hotel) => [...acc, {
        value: hotel.id,
        label: hotel.name,
      }], [{ value: 0, label: '== Select ==' }]),
    )

    const filteredRoomTypes: ComputedRef<RoomType[]> = computed(() => (
      form.hotel_id
        ? props.roomTypes
          .filter((roomType) => (roomType.hotel_id === form.hotel_id))
        : props.roomTypes
    ))
    const roomTypeOptions: ComputedRef<DropdownOption[]> = computed(
      () => filteredRoomTypes.value.reduce((acc, roomType) => [...acc, {
        value: roomType.id.toString(),
        label: roomType.name,
      }], [{ value: '', label: '' }]),
    )

    return {
      pageTitle,
      submitText,
      isEditForm,
      hotelOptions,
      roomTypeOptions,
      form,
      onFormSubmit,
      onDeleteClick,
      objectId,
    }
  },
})
</script>
