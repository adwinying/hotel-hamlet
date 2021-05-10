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
        <inline-svg
          class="w-6"
          src="/img/icons/plus.svg" />
        New Room Type
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
} from 'vue'
import InlineSvg from 'vue-inline-svg'
import route from 'ziggy-js'
import ResultTableField from '@/types/ResultTableField'
import DropdownOption from '@/types/DropdownOption'
import Hotel from '@/types/Models/Hotel'
import useIndexSearch from '@/composables/useIndexSearch'

import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputText from '@/components/InputText.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import ResultTable from '@/components/ResultTable.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'

export default defineComponent({
  name: 'RoomTypeIndex',

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

    const { searchParams } = useIndexSearch(props.query, {
      hotel_id: '',
      name: '',
    })

    const createUrl = route('room_types.create')

    return {
      fields,
      searchParams,
      formatter,
      hotelOptions,
      createUrl,
    }
  },
})
</script>
