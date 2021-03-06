<template>
  <page title="Room Types">
    <index-search-wrapper>
      <input-dropdown
        v-model="searchParams.hotel_id"
        name="hotel_id"
        :options="hotelOptions"
        label="Hotel"
        class="w-72" />

      <input-text
        v-model="searchParams.name"
        name="name"
        label="Room Type"
        class="w-72" />
    </index-search-wrapper>

    <result-cta-wrapper>
      <loading-button
        as="inertia-link"
        :href="createUrl">
        <plus-icon class="w-6" />
        New Room Type
      </loading-button>
    </result-cta-wrapper>

    <result-table
      :fields="fields"
      :formatter="formatter"
      :data="result.data" />

    <result-pagination :pagination-params="paginationParams" />
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
import route from 'ziggy-js'
import { PlusIcon } from '@heroicons/vue/solid'

import ResultTableField from '@/types/ResultTableField'
import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import usePagination from '@/composables/usePagination'
import useIndexSearch from '@/composables/useIndexSearch'

import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputText from '@/components/InputText.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import ResultTable from '@/components/ResultTable.vue'
import ResultPagination from '@/components/ResultPagination.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'
import LoadingButton from '@/components/LoadingButton.vue'

export default defineComponent({
  name: 'RoomTypeIndex',

  components: {
    IndexSearchWrapper,
    InputText,
    InputDropdown,
    ResultTable,
    ResultPagination,
    ResultCtaWrapper,
    LoadingButton,
    PlusIcon,
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
  },

  setup(props) {
    const fields: ResultTableField[] = [
      {
        key: 'hotel',
        label: 'Hotel',
      },
      {
        key: 'name',
        label: 'Room Type',
      },
    ]

    const formatter = {
      hotel: (data: Hotel) => data.name,
    }

    const hotelOptions: ComputedRef<DropdownOption[]> = computed(
      () => props.hotels.reduce((acc, hotel) => [...acc, {
        value: hotel.id.toString(),
        label: hotel.name,
      }], [{ value: '', label: '' }]),
    )

    const { paginationParams } = usePagination(toRef(props, 'result'))

    const { searchParams } = useIndexSearch(props.query, {
      hotel_id: '',
      name: '',
    })

    const createUrl = route('room_types.create')

    return {
      fields,
      searchParams,
      paginationParams,
      formatter,
      hotelOptions,
      createUrl,
    }
  },
})
</script>
