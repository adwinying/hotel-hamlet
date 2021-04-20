import { Ref, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import debounce from 'lodash/debounce'

interface UseIndexSearch {
  searchParams: Ref<Record<string, unknown>>
}
export default function useIndexSearch(
  query: Record<string, unknown>,
  initialSearchParams: Record<string, unknown>,
): UseIndexSearch {
  const searchParams = ref({ ...initialSearchParams })

  Object.keys(searchParams.value).forEach((key) => {
    searchParams.value[key] = query[key] ?? searchParams.value[key]
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
