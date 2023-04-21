<template>
  <Page title="Room Types">
    <IndexSearchWrapper>
      <InputDropdown
        v-model="searchParams.hotel_id"
        name="hotel_id"
        :options="hotelOptions"
        label="Hotel"
        class="w-72" />

      <InputText
        v-model="searchParams.name"
        name="name"
        label="Room Type"
        class="w-72" />
    </IndexSearchWrapper>

    <ResultCtaWrapper>
      <LoadingButton
        as="inertia-link"
        :href="createUrl">
        <PlusIcon class="w-6" />
        New Room Type
      </LoadingButton>
    </ResultCtaWrapper>

    <ResultTable
      :fields="fields"
      :data="result.data" />

    <ResultPagination :pagination-params="paginationParams" />
  </Page>
</template>

<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/solid'
import { computed, PropType, toRef } from 'vue'
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
import ResultTableField from '@/types/ResultTableField'

type PageProps = App.Http.Responses.RoomTypeIndexResponse
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
})

const fields: ResultTableField[] = [
  {
    key: 'hotel_name',
    label: 'Hotel',
  },
  {
    key: 'name',
    label: 'Room Type',
  },
]

const hotelOptions = computed(() =>
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

const { paginationParams } = usePagination(toRef(props, 'result'))

const { searchParams } = useIndexSearch(props.query, {
  hotel_id: '',
  name: '',
})

const createUrl = route('room_types.create')
</script>
