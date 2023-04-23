<template>
  <Page title="Hotels">
    <IndexSearchWrapper>
      <InputText
        v-model="searchParams.name"
        name="name"
        label="Name"
        class="w-72" />

      <InputDropdown
        v-model="searchParams.is_hidden"
        name="is_hidden"
        :options="hiddenOptions"
        label="Is Hidden?"
        class="w-72" />
    </IndexSearchWrapper>

    <ResultCtaWrapper>
      <LoadingButton
        :as="Link"
        :href="createUrl">
        <PlusIcon class="w-6" />
        Create Hotel
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
import { Link } from '@inertiajs/vue3'
import { PropType, computed } from 'vue'
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
import ResultTableField from '@/types/ResultTableField'

type PageProps = App.Http.Responses.Admin.HotelIndexResponse
const props = defineProps({
  query: {
    type: Object as PropType<PageProps['query']>,
    required: true,
  },

  result: {
    type: Object as PropType<PageProps['result']>,
    required: true,
  },
})

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

const { paginationParams } = usePagination(computed(() => props.result.meta))

const { searchParams } = useIndexSearch(props.query, {
  is_hidden: '',
  name: '',
})

const createUrl = route('hotels.create')
</script>
