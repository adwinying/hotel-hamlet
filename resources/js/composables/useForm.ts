import { computed, ComputedRef, Ref } from 'vue'
import route from 'ziggy-js'
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

  const currentRoute = route().current()

  const storeObject = () => {
    const storeRouteName = currentRoute.replace(/[^.]+$/, 'store')
    const storeUrl = route(storeRouteName)

    form.clearErrors()
    form.post(storeUrl, {
      onSuccess: () => {
        showToast('Successfully created.', 'success')
      },
    })
  }
  const updateObject = () => {
    const updateRouteName = currentRoute.replace(/[^.]+$/, 'update')
    const updateUrl = route(updateRouteName, objectId.value as number)

    form.clearErrors()
    form.put(updateUrl, {
      onSuccess: () => {
        showToast('Successfully updated.', 'success')
      },
    })
  }
  const deleteObject = () => {
    const destroyRouteName = currentRoute.replace(/[^.]+$/, 'destroy')
    const destroyUrl = route(destroyRouteName, objectId.value as number)

    form.clearErrors()
    form.delete(destroyUrl, {
      onSuccess: () => {
        showToast('Successfully deleted.', 'success')
      },
    })
  }
  const onFormSubmit = () => (
    isEditForm.value ? updateObject() : storeObject()
  )
  const onDeleteClick = () => deleteObject()

  return {
    form,
    objectId,
    isEditForm,
    onFormSubmit,
    onDeleteClick,
  }
}
