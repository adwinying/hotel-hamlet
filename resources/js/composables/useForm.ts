import { computed, ComputedRef } from 'vue'
import { InertiaForm, useForm as useInertiaForm, usePage } from '@inertiajs/inertia-vue3'

import { showToast } from '@/composables/useAlert'
import Model from '@/types/Models/Model'
import FormData from '@/types/FormData'

interface UseForm {
  form: InertiaForm<FormData>
  objectId: ComputedRef<number | null>
  isEditForm: ComputedRef<boolean>
  onFormSubmit: () => void
}
export default function useForm(
  object: Model | null,
  initialFormData: FormData,
): UseForm {
  const form = useInertiaForm({ ...initialFormData })

  Object.keys(initialFormData).forEach((key) => {
    form[key] = (object as unknown as FormData)?.[key] ?? form[key]
  })

  const objectId = computed(() => object?.id ?? null)
  const isEditForm = computed(() => objectId.value !== null)

  const onFormSubmit = () => {
    form.clearErrors()
    form.put(usePage().url.value, {
      onSuccess: () => {
        showToast('Successfully updated.', 'success')
      },
    })
  }

  return {
    form,
    objectId,
    isEditForm,
    onFormSubmit,
  }
}
