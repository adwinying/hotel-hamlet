import { reactive, UnwrapRef } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { debouncedWatch } from '@vueuse/core'

import SearchParams from '@/types/SearchParams'
import InputData from '@/types/InputData'

interface UseIndexSearch {
  searchParams: UnwrapRef<SearchParams>
}
export default function useIndexSearch(
  query: Record<string, unknown>,
  initialSearchParams: SearchParams,
): UseIndexSearch {
  const searchParams = reactive({ ...initialSearchParams })

  Object.keys(searchParams).forEach((key) => {
    searchParams[key] = (query[key] as InputData) ?? searchParams[key]
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
