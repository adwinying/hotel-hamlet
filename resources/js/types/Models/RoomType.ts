import Model from '@/types/Models/Model'
import Hotel from '@/types/Models/Hotel'

interface RoomType extends Model {
  hotel_id: number
  name: string
  hotel?: Hotel
}

export default RoomType
