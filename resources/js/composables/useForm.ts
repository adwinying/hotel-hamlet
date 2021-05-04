import { computed, ComputedRef, Ref } from 'vue'
import { InertiaForm, useForm as useInertiaForm } from '@inertiajs/inertia-vue3'

import { showToast } from '@/composables/useAlert'
import Model from '@/types/Models/Model'
import FormData from '@/types/FormData'

interface UseForm {
  form: InertiaForm<FormData>
  objectId: ComputedRef<number | null>
  isEditForm: ComputedRef<boolean>
  onFormSubmit: () => void
  onDeleteClick: () => void
}
export default function useForm(
  object: Ref<Model | null>,
  initialFormData: FormData,
): UseForm {
  const form = useInertiaForm({ ...initialFormData })

  Object.keys(initialFormData).forEach((key) => {
    form[key] = (object.value as unknown as FormData)?.[key] ?? form[key]
  })

  const objectId = computed(() => object.value?.id ?? null)
  const isEditForm = computed(() => objectId.value !== null)

  const onFormSubmit = () => {
    form.clearErrors()
    form.put(usePage().url.value, {
      onSuccess: () => {
        showToast('Successfully updated.', 'success')
      },
    })
  }

  const onDeleteClick = () => {
    form.clearErrors()
    form.delete(usePage().url.value, {
      onSuccess: () => {
        showToast('Successfully deleted.', 'success')
      },
    })
  }

  return {
    form,
    objectId,
    isEditForm,
    onFormSubmit,
    onDeleteClick,
  }
}
