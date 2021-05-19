import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { debouncedWatch } from '@vueuse/core'

import SearchParams from '@/types/SearchParams'
import InputData from '@/types/InputData'

interface UseIndexSearch<TParams> {
  searchParams: TParams
}
export default function useIndexSearch<TParams extends SearchParams>(
  query: Record<string, InputData>,
  initialSearchParams: TParams,
): UseIndexSearch<TParams> {
  const searchParams = reactive({ ...initialSearchParams }) as TParams

  Object.keys(searchParams).forEach((key) => {
    (searchParams as SearchParams)[key] = query[key] ?? searchParams[key]
  })

  const applySearchParams = () => {
    Inertia.visit(window.location.pathname, {
      data: searchParams,
      replace: true,
      preserveState: true,
    })
  }

  debouncedWatch(
    searchParams,
    applySearchParams,
    { debounce: 500, deep: true },
  )

  return {
    searchParams,
  }
}
