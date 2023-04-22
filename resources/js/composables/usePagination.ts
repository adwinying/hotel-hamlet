import { ComputedRef, computed } from 'vue'

export interface PaginationParams {
  from: number
  to: number
  total: number
  currentPage: number
  lastPage: number
  perPage: number
}

interface UsePagination {
  paginationParams: ComputedRef<PaginationParams>
}
export default function usePagination(
  paginationData: ComputedRef<{
    current_page: number
    first_page_url: string
    from: number | null
    last_page: number
    last_page_url: string
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number | null
    total: number
  }>,
): UsePagination {
  const paginationParams = computed(() => ({
    from: paginationData.value.from ?? 0,
    to: paginationData.value.to ?? 0,
    total: paginationData.value.total,
    currentPage: paginationData.value.current_page,
    lastPage: paginationData.value.last_page,
    perPage: paginationData.value.per_page,
  }))

  return {
    paginationParams,
  }
}
