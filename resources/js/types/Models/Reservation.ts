import Model from '@/types/Models/Model'
import Room from '@/types/Models/Room'

interface Reservation extends Model {
  room_id: number
  check_in_date: string
  check_out_date: string
  guest_name: string
  guest_email: string
  remarks: string
  room?: Room
}

export default Reservation
