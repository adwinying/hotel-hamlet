import Swal, {
  SweetAlertOptions,
  SweetAlertIcon,
  SweetAlertResult,
} from 'sweetalert2'

const commonConfig: SweetAlertOptions = {
}

const toastConfig: SweetAlertOptions = {
  timer: 5000,
  timerProgressBar: true,
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
}

type ShowToast = (message: string, type: SweetAlertIcon) => Promise<SweetAlertResult>
export const showToast:ShowToast = (message, type) => Swal.fire({
  ...commonConfig,
  ...toastConfig,
  title: message,
  icon: type,
})

interface UseAlert {
  showToast: ShowToast,
}
export default function useAlert(): UseAlert {
  return {
    showToast,
  }
}
