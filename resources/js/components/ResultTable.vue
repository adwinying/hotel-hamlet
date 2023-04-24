<template>
  <ResultTableWrapper>
    <ResultTableRow>
      <ResultTableHeader
        v-for="field in fields"
        :key="field.key">
        {{ field.label || field.key }}
      </ResultTableHeader>

      <ResultTableHeader />
    </ResultTableRow>

    <ResultTableRow
      v-for="row in data"
      :key="row.id">
      <ResultTableCell
        v-for="field in fields"
        :key="`${row.id}.${field.key}`"
        :href="formatRowUrl(row.id, row)">
        {{ formatDataCell(row, field.key) }}
      </ResultTableCell>

      <ResultTableCell :href="formatRowUrl(row.id, row)">
        <ChevronRightIcon class="w-6 text-gray-500" />
      </ResultTableCell>
    </ResultTableRow>
  </ResultTableWrapper>
</template>

<script setup lang="ts">
import { ChevronRightIcon } from '@heroicons/vue/solid'
import { PropType } from 'vue'
import route from 'ziggy-js'

import ResultTableCell from '@/components/ResultTableCell.vue'
import ResultTableHeader from '@/components/ResultTableHeader.vue'
import ResultTableRow from '@/components/ResultTableRow.vue'
import ResultTableWrapper from '@/components/ResultTableWrapper.vue'
import Model from '@/types/Model'
import ResultTableField from '@/types/ResultTableField'

const props = defineProps({
  fields: {
    type: Array as PropType<ResultTableField[]>,
    default: () => [],
  },

  data: {
    type: Array as PropType<Model[]>,
    default: () => [],
  },

  formatter: {
    type: Object,
    default: () => ({}),
  },

  basePath: {
    type: [String, Function],
    default: () => (id: number) => {
      const currRoute = route().current()
      const showRoute = route(currRoute.replace('index', 'show'), id)

      return showRoute
    },
  },
})

const formatDataCell = (rowData: Model, key: string): string | number => {
  const formatter = props.formatter[key] as
    | ((cellData: unknown, rowData: Model) => string | number)
    | undefined

  return formatter
    ? formatter(rowData[key], rowData)
    : (rowData[key] as string | number)
}

const formatRowUrl = (rowId: number, rowData: Model): string => {
  const basePath = props.basePath as
    | ((id: number, data: Model) => string)
    | string

  return typeof basePath === 'string'
    ? `${basePath}/${rowId}`
    : basePath(rowId, rowData)
}
</script>
