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
        as="inertia-link"
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
import { toRef } from 'vue'
import { PlusIcon } from '@heroicons/vue/solid'
import route from 'ziggy-js'

import ResultTableField from '@/types/ResultTableField'
import DropdownOption from '@/types/DropdownOption'
import usePagination from '@/composables/usePagination'
import useIndexSearch from '@/composables/useIndexSearch'

import Page from '@/components/Page.vue'
import IndexSearchWrapper from '@/components/IndexSearchWrapper.vue'
import InputText from '@/components/InputText.vue'
import InputDropdown from '@/components/InputDropdown.vue'
import ResultTable from '@/components/ResultTable.vue'
import ResultPagination from '@/components/ResultPagination.vue'
import ResultCtaWrapper from '@/components/ResultCtaWrapper.vue'

import LoadingButton from '@/components/LoadingButton.vue'

const props = defineProps({
  query: {
    type: Object,
    required: true,
  },

  result: {
    type: Object,
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

const { paginationParams } = usePagination(toRef(props, 'result'))

const { searchParams } = useIndexSearch(props.query, {
  is_hidden: '',
  name: '',
})

const createUrl = route('hotels.create')
</script>
