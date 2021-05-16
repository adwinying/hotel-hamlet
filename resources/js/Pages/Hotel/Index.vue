<template>
  <page title="Hotels">
    <index-search-wrapper>
      <input-text
        v-model="searchParams.name"
        name="name"
        label="Name"
        class="w-72" />

      <input-dropdown
        v-model="searchParams.is_hidden"
        name="is_hidden"
        :options="hiddenOptions"
        label="Is Hidden?"
        class="w-72" />
    </index-search-wrapper>

    <result-cta-wrapper>
      <loading-button
        as="inertia-link"
        :href="createUrl">
        <plus-icon class="w-6" />
        Create Hotel
      </loading-button>
    </result-cta-wrapper>

    <result-table
      :fields="fields"
      :formatter="formatter"
      :data="result.data" />
  </page>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { PlusIcon } from '@heroicons/vue/solid'
import route from 'ziggy-js'

import ResultTableField from '@/types/ResultTableField'
import DropdownOption from '@/types/DropdownOption'
import useIndexSearch from '@/composables/useIndexSearch'

import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputText from '@/components/InputText.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import ResultTable from '@/components/ResultTable.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'

export default defineComponent({
  name: 'HotelIndex',

  components: {
    IndexSearchWrapper,
    InputText,
    InputDropdown,
    ResultTable,
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
  },

  setup(props) {
    const fields: ResultTableField[] = [
      {
        key: 'name',
        label: 'Name',
      },
      {
        key: 'is_hidden',
        label: 'Is Hidden?',
      },
    ]

    const formatter = {
      is_hidden: (data: boolean) => (data ? 'Yes' : 'No'),
    }

    const hiddenOptions: DropdownOption[] = [
      {
        value: '',
        label: '',
      },
      {
        value: 1,
        label: 'Yes',
      },
      {
        value: 0,
        label: 'No',
      },
    ]

    const { searchParams } = useIndexSearch(props.query, {
      is_hidden: '',
      name: '',
    })

    const createUrl = route('hotels.create')

    return {
      fields,
      searchParams,
      formatter,
      hiddenOptions,
      createUrl,
    }
  },
})
</script>
