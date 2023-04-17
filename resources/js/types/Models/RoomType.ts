import Hotel from '@/types/Models/Hotel'
import Model from '@/types/Models/Model'

interface RoomType extends Model {
  hotel_id: number
  name: string
  price: number
  hotel?: Hotel
}

export default RoomType
