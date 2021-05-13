import Model from '@/types/Models/Model'
import RoomType from '@/types/Models/RoomType'

interface Room extends Model {
  room_type_id: number
  room_no: string
  room_type?: RoomType
}

export default Room
