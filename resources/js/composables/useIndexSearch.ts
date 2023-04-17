import { router } from '@inertiajs/vue3'
import { debouncedWatch } from '@vueuse/core'
import { reactive } from 'vue'

import InputData from '@/types/InputData'
import SearchParams from '@/types/SearchParams'

interface UseIndexSearch<TParams> {
  searchParams: TParams
}
export default function useIndexSearch<TParams extends SearchParams>(
  query: Record<string, InputData>,
  initialSearchParams: TParams,
): UseIndexSearch<TParams> {
  const searchParams = reactive({ ...initialSearchParams }) as TParams

  Object.keys(searchParams).forEach((key) => {
    ;(searchParams as SearchParams)[key] = query[key] ?? searchParams[key]
  })

  const applySearchParams = () => {
    router.visit(window.location.pathname, {
      data: searchParams,
      replace: true,
      preserveState: true,
    })
  }

  debouncedWatch(searchParams, applySearchParams, { debounce: 500, deep: true })

  return {
    searchParams,
  }
}
