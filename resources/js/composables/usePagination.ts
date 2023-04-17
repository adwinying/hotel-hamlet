import { reactive, Ref, watch } from 'vue'

import PaginationParams from '@/types/PaginationParams'

interface UsePagination {
  paginationParams: PaginationParams
}
export default function usePagination(
  results: Ref<Record<string, unknown>>,
): UsePagination {
  const paginationParams = reactive({
    from: 0,
    to: 0,
    total: 0,
    currentPage: 0,
    lastPage: 0,
    perPage: 0,
  })

  watch(
    results,
    () => {
      const res = results.value

      paginationParams.from = res.from as number
      paginationParams.to = res.to as number
      paginationParams.total = res.total as number
      paginationParams.currentPage = res.current_page as number
      paginationParams.lastPage = res.last_page as number
      paginationParams.perPage = res.per_page as number
    },
    { deep: true, immediate: true },
  )

  return {
    paginationParams,
  }
}
