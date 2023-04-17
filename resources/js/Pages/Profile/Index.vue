<template>
  <Page title="Profile Settings">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <InputText
          v-model="form.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <InputText
          v-model="form.email"
          :errors="form.errors.email"
          name="email"
          label="Email"
          class="lg:col-span-4" />

        <InputText
          v-model="form.old_password"
          :errors="form.errors.old_password"
          name="old_password"
          type="password"
          label="Old Password"
          class="lg:col-span-4" />

        <InputText
          v-model="form.password"
          :errors="form.errors.password"
          name="password"
          type="password"
          label="New Password"
          class="lg:col-span-4" />

        <InputText
          v-model="form.password_confirmation"
          :errors="form.errors.password_confirmation"
          name="password_confirmation"
          type="password"
          label="New Password (Confirm)"
          class="lg:col-span-4" />
      </div>

      <hr class="my-6 border-t" />

      <div class="flex justify-end">
        <LoadingButton
          type="submit"
          :is-loading="form.processing">
          Update Settings
        </LoadingButton>
      </div>
    </form>
  </Page>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { PropType } from 'vue'

import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import Page from '@/components/Page.vue'
import { showToast } from '@/composables/useAlert'

interface Profile {
  name: string
  email: string
}
const props = defineProps({
  profile: {
    type: Object as PropType<Profile>,
    required: true,
  },
})

const form = useForm({
  name: props.profile.name,
  email: props.profile.email,
  old_password: '',
  password: '',
  password_confirmation: '',
})

const onFormSubmit = () => {
  form.clearErrors()
  form.post('/admin/profile', {
    onSuccess: () => {
      showToast('Profile successfully updated', 'success')
      form.reset('old_password', 'password', 'password_confirmation')
    },
  })
}
</script>
