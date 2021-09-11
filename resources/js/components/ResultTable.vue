<template>
  <result-table-wrapper>
    <result-table-row>
      <result-table-header
        v-for="field in fields"
        :key="field.key">
        {{ field.label || field.key }}
      </result-table-header>

      <result-table-header />
    </result-table-row>

    <result-table-row
      v-for="row in data"
      :key="row.id">
      <result-table-cell
        v-for="field in fields"
        :key="`${row.id}.${field.key}`"
        :href="formatRowUrl(row.id, row)">
        {{ formatDataCell(row, field.key) }}
      </result-table-cell>

      <result-table-cell :href="formatRowUrl(row.id, row)">
        <chevron-right-icon class="w-6 text-gray-500" />
      </result-table-cell>
    </result-table-row>
  </result-table-wrapper>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import route from 'ziggy-js'
import { ChevronRightIcon } from '@heroicons/vue/solid'

import Model from '@/types/Models/Model'
import ResultTableField from '@/types/ResultTableField'
import ResultTableFormatter from '@/types/ResultTableFormatter'

import ResultTableCell from '@/components/ResultTableCell.vue'
import ResultTableHeader from '@/components/ResultTableHeader.vue'
import ResultTableRow from '@/components/ResultTableRow.vue'
import ResultTableWrapper from '@/components/ResultTableWrapper.vue'

export default defineComponent({
  name: 'ResultTable',

  components: {
    ResultTableWrapper,
    ResultTableRow,
    ResultTableHeader,
    ResultTableCell,
    ChevronRightIcon,
  },

  props: {
    fields: {
      type: Object as PropType<ResultTableField[]>,
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
  },

  setup(props) {
    const formatDataCell = (
      rowData: Model,
      key: string,
    ): string | number => (
      props.formatter[key]
        ? props.formatter[key](rowData[key], rowData)
        : rowData[key] as string | number
    )

    const formatRowUrl = (
      rowId: number,
      rowData: Model,
    ): string => (
      typeof props.basePath === 'function'
        ? props.basePath(rowId, rowData)
        : `${props.basePath}/${rowId}`
    )

    return {
      formatDataCell,
      formatRowUrl,
    }
  },
})
</script>
