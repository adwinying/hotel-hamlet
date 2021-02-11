<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50
    py-12 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-5 rounded-md shadow-md">
      <div>
        <img
          class="mx-auto h-24 w-auto"
          src="/img/logo.svg"
          alt="Hotel Hamelet Logo">
        <h2 class="mt-6 text-center text-3xl font-display text-gray-900">
          Hotel Hamlet Admin Login
        </h2>
      </div>

      <form
        class="mt-8 space-y-6"
        @submit.prevent="onFormSubmit">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label
              for="email-address"
              class="sr-only">Email address</label>
            <input
              id="email-address"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none rounded-none relative block w-full
              px-3 py-2 border border-gray-300 placeholder-gray-500
              text-gray-900 rounded-t-md
              focus:outline-none focus:ring-red-700 focus:border-red-700
              focus:z-10 sm:text-sm"
              placeholder="Email address">
          </div>

          <div>
            <label
              for="password"
              class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="appearance-none rounded-none relative block w-full
              px-3 py-2 border border-gray-300 placeholder-gray-700
              text-gray-900 rounded-b-md
              focus:outline-none focus:ring-red-700 focus:border-red-700
              focus:z-10 sm:text-sm"
              placeholder="Password">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember_me"
              v-model="form.remember"
              name="remember_me"
              type="checkbox"
              class="h-4 w-4 text-red-600 focus:ring-red-700 border-gray-300 rounded">
            <label
              for="remember_me"
              class="ml-2 block text-sm text-gray-900">
              Remember me
            </label>
          </div>
        </div>

        <div>
          <button
            :disabled="form.processing"
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4
            border border-transparent text-sm font-medium rounded-md
            text-white bg-red-700 hover:bg-red-800 focus:outline-none
            focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <!-- Heroicon name: solid/lock-closed -->
              <svg
                class="h-5 w-5 text-red-500 group-hover:text-red-500"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true">
                <path
                  fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0
                  01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd" />
              </svg>
            </span>
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'

export default defineComponent({
  props: {
    errors: {
      type: Object,
      required: true,
    },
  },

  setup() {
    const form = useForm({
      email: 'admin@example.com',
      password: 'secret',
      remember: false,
    })

    const onFormSubmit = () => {
      form.value.post('/admin/login')
    }

    return {
      form,
      onFormSubmit,
    }
  },
})
</script>
