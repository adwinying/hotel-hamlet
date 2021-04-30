import { Ref, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import debounce from 'lodash/debounce'

import SearchParams from '@/types/SearchParams'
import InputData from '@/types/InputData'

interface UseIndexSearch {
  searchParams: Ref<SearchParams>
}
export default function useIndexSearch(
  query: Record<string, unknown>,
  initialSearchParams: SearchParams,
): UseIndexSearch {
  const searchParams = ref({ ...initialSearchParams })

  Object.keys(searchParams.value).forEach((key) => {
    searchParams.value[key] = (query[key] as InputData)
      ?? searchParams.value[key]
  })

  const applySearchParams = () => {
    Inertia.visit(window.location.pathname, {
      data: searchParams.value,
      replace: true,
      preserveState: true,
    })
  }

  watch(searchParams, debounce(applySearchParams, 500), { deep: true })

  return {
    searchParams,
  }
}
