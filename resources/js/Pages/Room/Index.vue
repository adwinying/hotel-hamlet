<template>
  <page title="Rooms">
    <index-search-wrapper>
      <input-dropdown
        v-model="searchParams.hotel_id"
        name="hotel_id"
        :options="hotelOptions"
        label="Hotel"
        class="w-72" />

      <input-dropdown
        v-model="searchParams.room_type_id"
        name="room_type_id"
        :options="roomTypeOptions"
        label="Room Type"
        class="w-72" />

      <input-text
        v-model="searchParams.room_no"
        name="name"
        label="Room No."
        class="w-72" />
    </index-search-wrapper>

    <result-cta-wrapper>
      <loading-button
        as="inertia-link"
        :href="createUrl">
        <inline-svg
          class="w-6"
          src="/img/icons/plus.svg" />
        New Room
      </loading-button>
    </result-cta-wrapper>

    <result-table
      :fields="fields"
      :formatter="formatter"
      :data="result.data" />
  </page>
</template>

<script lang="ts">
import {
  computed,
  ComputedRef,
  defineComponent,
  PropType,
  watch,
} from 'vue'
import InlineSvg from 'vue-inline-svg'
import route from 'ziggy-js'
import ResultTableField from '@/types/ResultTableField'
import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import RoomType from '@/types/Models/RoomType'
import Room from '@/types/Models/Room'
import useIndexSearch from '@/composables/useIndexSearch'

import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputText from '@/components/InputText.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import ResultTable from '@/components/ResultTable.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'

export default defineComponent({
  name: 'RoomIndex',

  components: {
    IndexSearchWrapper,
    InputText,
    InputDropdown,
    ResultTable,
    ResultCtaWrapper,
    InlineSvg,
    LoadingButton,
  },

  props: {
    query: {
      type: Object,
      required: true,
    },

    result: {
      type: Object,
      required: true,
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
    const fields: ResultTableField[] = [
      {
        key: 'hotel',
        label: 'Hotel',
      },
      {
        key: 'room_type',
        label: 'Room Type',
      },
      {
        key: 'room_no',
        label: 'Room No.',
      },
    ]

    const formatter = {
      room_type: (data: RoomType) => data.name,
      hotel: (_: undefined, rowData: Room) => rowData.room_type?.hotel?.name,
    }

    const { searchParams } = useIndexSearch(props.query, {
      hotel_id: '',
      room_type_id: '',
      name: '',
    })

    watch(
      () => searchParams.value.hotel_id,
      () => { searchParams.value.room_type_id = '' },
    )

    const hotelOptions: ComputedRef<DropdownOption[]> = computed(
      () => props.hotels.reduce((acc, hotel) => [...acc, {
        value: hotel.id.toString(),
        label: hotel.name,
      }], [{ value: '', label: '' }]),
    )

    const filteredRoomTypes: ComputedRef<RoomType[]> = computed(() => (
      searchParams.value.hotel_id
        ? props.roomTypes.filter((roomType) => (
          roomType.hotel_id === parseInt(searchParams.value.hotel_id as string, 10)
        ))
        : props.roomTypes
    ))
    const roomTypeOptions: ComputedRef<DropdownOption[]> = computed(
      () => filteredRoomTypes.value.reduce((acc, roomType) => [...acc, {
        value: roomType.id.toString(),
        label: roomType.name,
      }], [{ value: '', label: '' }]),
    )

    const createUrl = route('room_types.create')

    return {
      fields,
      searchParams,
      formatter,
      hotelOptions,
      roomTypeOptions,
      filteredRoomTypes,
      createUrl,
    }
  },
})
</script>
