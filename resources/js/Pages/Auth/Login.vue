<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50
    py-12 lg:px-8">
    <div
      class="container flex flex-col max-w-md p-6 mx-auto rounded-md
      sm:p-8 bg-white text-gray-800 space-y-6 sm:shadow-md">
      <div>
        <img
          class="mx-auto h-24 w-auto"
          src="/img/logo.svg"
          alt="Hotel Hamlet Logo">
        <h2 class="mt-6 text-center text-3xl font-display text-gray-900">
          Hotel Hamlet Admin Login
        </h2>
      </div>

      <form
        class="space-y-6"
        @submit.prevent="onFormSubmit">
        <div class="space-y-4">
          <div>
            <label
              for="email"
              class="block mb-2 text-sm">Email address</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              name="email"
              class="w-full px-3 py-2 border rounded-md border-gray-300
              bg-gray-50 text-gray-800
              focus:outline-none focus:ring-cyan-500 focus:border-cyan-600">
          </div>
          <div>
            <label
              for="password"
              class="block mb-2 text-sm">Password</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              name="password"
              class="w-full px-3 py-2 border rounded-md border-gray-300
              bg-gray-50 text-gray-800
              focus:outline-none focus:ring-cyan-500 focus:border-cyan-600">
          </div>
        </div>

        <div
          v-if="form.hasErrors"
          class="text-red-500 text-center">
          Invalid credentials.
        </div>

        <button
          type="submit"
          class="w-full px-8 py-3 rounded-md bg-cyan-600
          hover:bg-cyan-800 text-gray-50
          focus:outline-none focus:ring-4 focus:ring-cyan-500 focus:ring-opacity-60">
          Sign in
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/inertia-vue3'

defineProps({
  errors: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  email: 'admin@example.com',
  password: 'password',
  remember: false,
})

const onFormSubmit = () => {
  form.clearErrors()
  form.post('/admin/login')
}
</script>
