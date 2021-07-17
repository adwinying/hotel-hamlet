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

        <input-text
          v-model="form.name"
          :errors="form.errors.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <input-text
          v-model="form.price"
          :errors="form.errors.price"
          name="price"
          label="Price"
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
} from 'vue'
import { TrashIcon } from '@heroicons/vue/outline'

import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import RoomType from '@/types/Models/RoomType'
import useForm from '@/composables/useForm'

import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'RoomTypeForm',

  components: {
    InputDropdown,
    InputText,
    LoadingButton,
    TrashIcon,
  },

  props: {
    roomType: {
      type: Object as PropType<RoomType | null>,
      default: null,
    },

    hotels: {
      type: Array as PropType<Hotel[]>,
      required: true,
    },
  },

  setup(props) {
    const initialFormData = {
      hotel_id: 0,
      name: '',
      price: '',
    }

    const {
      form,
      isEditForm,
      objectId,
      onFormSubmit,
      onDeleteClick,
    } = useForm(toRef(props, 'roomType'), initialFormData)

    const pageTitle = computed(() => (
      isEditForm.value ? 'Edit Room Type' : 'New Room Type'
    ))

    const submitText = computed(() => (
      isEditForm.value ? 'Update Room Type' : 'Create Room Type'
    ))

    const hotelOptions: ComputedRef<DropdownOption[]> = computed(
      () => props.hotels.reduce((acc, hotel) => [...acc, {
        value: hotel.id.toString(),
        label: hotel.name,
      }], [{ value: '', label: '' }]),
    )

    return {
      pageTitle,
      submitText,
      isEditForm,
      hotelOptions,
      form,
      onFormSubmit,
      onDeleteClick,
      objectId,
    }
  },
})
</script>
