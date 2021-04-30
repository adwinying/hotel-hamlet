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
        <inline-svg
          class="w-6 text-gray-500"
          src="/img/icons/chevron_right.svg" />
      </result-table-cell>
    </result-table-row>
  </result-table-wrapper>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import InlineSvg from 'vue-inline-svg'

import ResultTableField from '@/types/ResultTableField'
import ResultDataFormatter from '@/types/ResultDataFormatter'

import ResultTableCell from '@/components/ResultTableCell.vue'
import ResultTableHeader from '@/components/ResultTableHeader.vue'
import ResultTableRow from '@/components/ResultTableRow.vue'
import ResultTableWrapper from '@/components/ResultTableWrapper.vue'

export default defineComponent({
  name: 'ResultTable',

  components: {
    InlineSvg,
    ResultTableWrapper,
    ResultTableRow,
    ResultTableHeader,
    ResultTableCell,
  },

  props: {
    fields: {
      type: Object as PropType<ResultTableField[]>,
      default: () => [],
    },

    data: {
      type: Array,
      default: () => [],
    },

    formatter: {
      type: Object,
      default: () => ({}),
    },

    basePath: {
      type: [String, Function],
      default: () => usePage().url.value.replace(/\?.*$/, ''),
    },
  },

  setup(props) {
    const formatDataCell = (
      rowData: Record<string, ResultDataFormatter>,
      key: string,
    ): string | number => (
      props.formatter[key]
        ? props.formatter[key](rowData[key], rowData)
        : rowData[key]
    )

    const formatRowUrl = (
      rowId: number,
      rowData: Record<string, ResultDataFormatter>,
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
