<template>
  <Page title="Rooms">
    <IndexSearchWrapper>
      <InputDropdown
        v-model="searchParams.hotel_id"
        name="hotel_id"
        :options="hotelOptions"
        label="Hotel"
        class="w-72" />

      <InputDropdown
        v-model="searchParams.room_type_id"
        name="room_type_id"
        :options="roomTypeOptions"
        label="Room Type"
        class="w-72" />

      <InputText
        v-model="searchParams.room_no"
        name="name"
        label="Room No."
        class="w-72" />
    </IndexSearchWrapper>

    <ResultCtaWrapper>
      <LoadingButton
        as="inertia-link"
        :href="createUrl">
        <PlusIcon class="w-6" />
        New Room
      </LoadingButton>
    </ResultCtaWrapper>

    <ResultTable
      :fields="fields"
      :formatter="formatter"
      :data="result.data" />

    <ResultPagination :pagination-params="paginationParams" />
  </Page>
</template>

<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/solid'
import { computed, PropType, toRef, watch } from 'vue'
import route from 'ziggy-js'

import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'
import ResultPagination from '@/components/ResultPagination.vue'
import ResultTable from '@/components/ResultTable.vue'
import useIndexSearch from '@/composables/useIndexSearch'
import usePagination from '@/composables/usePagination'
import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import Room from '@/types/Models/Room'
import RoomType from '@/types/Models/RoomType'
import ResultTableField from '@/types/ResultTableField'

const props = defineProps({
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
})

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

const { paginationParams } = usePagination(toRef(props, 'result'))

const { searchParams } = useIndexSearch(props.query, {
  hotel_id: '',
  room_type_id: '',
  room_no: '',
})

watch(
  () => searchParams.hotel_id,
  () => {
    searchParams.room_type_id = ''
  },
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

const filteredRoomTypes = computed<RoomType[]>(() =>
  searchParams.hotel_id
    ? props.roomTypes.filter(
        (roomType) => roomType.hotel_id === parseInt(searchParams.hotel_id, 10),
      )
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

const createUrl = route('rooms.create')
</script>
