import { InertiaForm, useForm as useInertiaForm } from '@inertiajs/vue3'
import { computed, ComputedRef, Ref } from 'vue'
import route from 'ziggy-js'

import { showToast } from '@/composables/useAlert'
import Model from '@/types/Models/Model'

interface UseForm<TForm extends Record<string, unknown>> {
  form: InertiaForm<TForm>
  objectId: ComputedRef<number | null>
  isEditForm: ComputedRef<boolean>
  onFormSubmit: () => void
  onDeleteClick: () => void
}
export default function useForm<TForm extends Record<string, unknown>>(
  object: Ref<Model | null>,
  initialFormData: TForm,
): UseForm<TForm> {
  const form = useInertiaForm({ ...initialFormData })

  Object.keys(initialFormData).forEach((key) => {
    ;(form as Record<string, unknown>)[key] = object.value?.[key] ?? form[key]
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
    if (objectId.value === null) throw new Error('objectId invalid!')

    const updateRouteName = currentRoute.replace(/[^.]+$/, 'update')
    const updateUrl = route(updateRouteName, objectId.value)

    form.clearErrors()
    form.put(updateUrl, {
      onSuccess: () => {
        showToast('Successfully updated.', 'success')
      },
    })
  }
  const deleteObject = () => {
    if (objectId.value === null) throw new Error('objectId invalid!')

    const destroyRouteName = currentRoute.replace(/[^.]+$/, 'destroy')
    const destroyUrl = route(destroyRouteName, objectId.value)

    form.clearErrors()
    form.delete(destroyUrl, {
      onSuccess: () => {
        showToast('Successfully deleted.', 'success')
      },
    })
  }
  const onFormSubmit = () => (isEditForm.value ? updateObject() : storeObject())
  const onDeleteClick = () => deleteObject()

  return {
    form,
    objectId,
    isEditForm,
    onFormSubmit,
    onDeleteClick,
  }
}
