<template>
  <Page title="Reservations">
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
        v-model="searchParams.check_in_date"
        name="check_in_date"
        label="Check In Date"
        type="date"
        class="w-72" />

      <InputText
        v-model="searchParams.check_out_date"
        name="check_in_date"
        label="Check Out Date"
        type="date"
        class="w-72" />

      <InputText
        v-model="searchParams.guest_name"
        name="guest_name"
        label="Guest Name"
        class="w-72" />

      <InputText
        v-model="searchParams.guest_email"
        name="guest_email"
        label="Guest Email"
        class="w-72" />
    </IndexSearchWrapper>

    <ResultCtaWrapper>
      <LoadingButton
        as="inertia-link"
        :href="createUrl">
        <PlusIcon class="w-6" />
        New Reservation
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
import { computed, ComputedRef, PropType, watch } from 'vue'
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
import Reservation from '@/types/Models/Reservation'
import ResultTableField from '@/types/ResultTableField'
import ResultTableFormatter from '@/types/ResultTableFormatter'

type PageProps = App.Http.Responses.ReservationIndexResponse
const props = defineProps({
  query: {
    type: Object as PropType<PageProps['query']>,
    required: true,
  },

  result: {
    type: Object as PropType<PageProps['result']>,
    required: true,
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

const fields: ResultTableField[] = [
  {
    key: 'hotel_name',
    label: 'Hotel',
  },
  {
    key: 'room_type_name',
    label: 'Room Type',
  },
  {
    key: 'check_in_date',
    label: 'Check In Date',
  },
  {
    key: 'check_out_date',
    label: 'Check Out Date',
  },
  {
    key: 'guest_name',
    label: 'Guest Name',
  },
]

const formatter: ResultTableFormatter<Reservation> = {
  check_in_date: (date) => (date as string).replace(/-/g, '/'),
  check_out_date: (date) => (date as string).replace(/-/g, '/'),
}

const { paginationParams } = usePagination(computed(() => props.result.meta))

const { searchParams } = useIndexSearch(props.query, {
  hotel_id: '',
  room_type_id: '',
  check_in_date: '',
  check_out_date: '',
  guest_name: '',
  guest_email: '',
})

watch(
  () => searchParams.hotel_id,
  () => {
    searchParams.room_type_id = ''
  },
)

const hotelOptions: ComputedRef<DropdownOption[]> = computed(() =>
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

const filteredRoomTypes = computed(() =>
  searchParams.hotel_id
    ? props.roomTypes.filter(
        (roomType) => roomType.hotel_id === parseInt(searchParams.hotel_id, 10),
      )
    : props.roomTypes,
)
const roomTypeOptions = computed(() =>
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

const createUrl = route('reservations.create')
</script>
