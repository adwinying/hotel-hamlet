<template>
  <page title="Profile Settings">
    <form @submit.prevent="onFormSubmit">
      <div class="grid grid-cols-6 gap-6">
        <input-text
          v-model="form.name"
          name="name"
          label="Name"
          class="lg:col-span-4" />

        <input-text
          v-model="form.email"
          :errors="form.errors.email"
          name="email"
          label="Email"
          class="lg:col-span-4" />

        <input-text
          v-model="form.old_password"
          :errors="form.errors.old_password"
          name="old_password"
          type="password"
          label="Old Password"
          class="lg:col-span-4" />

        <input-text
          v-model="form.password"
          :errors="form.errors.password"
          name="password"
          type="password"
          label="New Password"
          class="lg:col-span-4" />

        <input-text
          v-model="form.password_confirmation"
          :errors="form.errors.password_confirmation"
          name="password_confirmation"
          type="password"
          label="New Password (Confirm)"
          class="lg:col-span-4" />
      </div>

      <loading-button
        class="mt-6"
        type="submit"
        :is-loading="form.processing">
        Update Settings
      </loading-button>
    </form>
  </page>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { showToast } from '@/composables/useAlert'

import InputText from '@/components/InputText.vue'
import LoadingButton from '@/components/LoadingButton.vue'

interface Profile {
  name: string;
  email: string;
}
export default defineComponent({
  name: 'ProfileIndex',

  components: {
    InputText,
    LoadingButton,
  },

  props: {
    profile: {
      type: Object as PropType<Profile>,
      required: true,
    },
  },

  setup(props) {
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

    return {
      form,
      onFormSubmit,
    }
  },
})
</script>
