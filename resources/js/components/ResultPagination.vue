<template>
  <div class="bg-white my-5 flex items-center justify-between">
    <div
      v-if="lastPage > 1"
      class="flex-1 flex justify-between sm:hidden">
      <component
        :is="currentPage === 1 ? 'span' : 'inertia-link'"
        :href="generatePageHref((currentPage - 1).toString())"
        class="relative inline-flex items-center px-4 py-2
        border border-gray-300 text-sm font-medium rounded-md text-gray-700
        bg-white hover:bg-gray-50">
        Previous
      </component>

      <component
        :is="currentPage === lastPage ? 'span' : 'inertia-link'"
        :href="generatePageHref((currentPage + 1).toString())"
        class="ml-3 relative inline-flex items-center px-4 py-2
        border border-gray-300 text-sm font-medium rounded-md text-gray-700
        bg-white hover:bg-gray-50">
        Next
      </component>
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ paginationParams.from }}</span>
          to
          <span class="font-medium">{{ paginationParams.to }}</span>
          of
          <span class="font-medium">{{ paginationParams.total }}</span>
          results
        </p>
      </div>
      <div v-if="lastPage > 1">
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination">
          <component
            :is="currentPage === 1 ? 'span' : 'inertia-link'"
            :href="generatePageHref((currentPage - 1).toString())"
            class="relative inline-flex items-center px-2 py-2
            rounded-l-md border border-gray-300 bg-white
            text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon
              class="h-5 w-5"
              aria-hidden="true" />
          </component>

          <component
            :is="page === '...' ? 'span' : 'inertia-link'"
            v-for="page in pagesToShow"
            :key="page"
            :href="generatePageHref(page)"
            :aria-current="parseInt(page, 10) === currentPage ? 'page' : null"
            :class="parseInt(page, 10) === currentPage
              ? `z-10 bg-cyan-600 border-cyan-600 text-white
              relative inline-flex items-center
              px-4 py-2 border text-sm font-medium`
              : `bg-white border-gray-300 text-gray-500
              hover:bg-gray-50 relative inline-flex items-center
              px-4 py-2 border text-sm font-medium`">
            {{ page }}
          </component>

          <component
            :is="currentPage === lastPage ? 'span' : 'inertia-link'"
            :href="generatePageHref((currentPage + 1).toString())"
            class="relative inline-flex items-center px-2 py-2
            rounded-r-md border border-gray-300 bg-white text-sm
            font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Next</span>
            <ChevronRightIcon
              class="h-5 w-5"
              aria-hidden="true" />
          </component>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, PropType } from 'vue'
import { toRefs } from '@vueuse/core'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'

import PaginationParams from '@/types/PaginationParams'
import { usePage } from '@inertiajs/inertia-vue3'

const props = defineProps({
  paginationParams: {
    type: Object as PropType<PaginationParams>,
    required: true,
  },

  maxShowPages: {
    type: Number,
    default: 9,
  },
})

const { lastPage, currentPage } = toRefs(props.paginationParams)

const pagesToShow = computed(() => {
  if (lastPage.value <= 1) return []

  // exclude first, last and current pages
  const subpagesToShow = props.maxShowPages - 3
  const subpagesForEachSide = Math.floor(subpagesToShow / 2)
  const leftmostPage = currentPage.value - subpagesForEachSide
  const rightmostPage = currentPage.value + subpagesForEachSide

  let result = []

  // push surrounding pages
  for (let i = leftmostPage; i <= rightmostPage; i += 1) result.push(i.toString())

  // filter out pages that are out of bounds
  result = result.filter((page) => {
    const pageNum = parseInt(page, 10)

    return pageNum > 1 && pageNum < lastPage.value
  })

  // if neccessary, add '...'
  if (result.length > 0) {
    if (result[0] !== '2') result.unshift('...')
    if (result[result.length - 1] !== (lastPage.value - 1).toString()) result.push('...')
  }

  // append first and last pages
  result.unshift('1')
  result.push(lastPage.value.toString())

  return result
})

const generatePageHref = (page: string): string => {
  const url = usePage().url.value
  const queryRegexp = /\?/
  const pageRegexp = /page=\d*/

  if (!queryRegexp.test(url)) return `${url}?page=${page}`

  return pageRegexp.test(url)
    ? url.replace(pageRegexp, `page=${page}`)
    : `${url}&page=${page}`
}
</script>
