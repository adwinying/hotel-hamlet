import Room from '@/types/Models/Room'

type FetchAvailableRooms = (
  roomTypeId: number,
  checkInDate: string,
  checkOutDate: string,
) => Promise<Room[]>
export const fetchAvailableRooms: FetchAvailableRooms = async (
  roomTypeId,
  checkInDate,
  checkOutDate,
) => {
  const endpoint = '/api/admin/room_availability'

  const res = await fetch(`${endpoint}?room_type_id=${roomTypeId}&check_in_date=${checkInDate}&check_out_date=${checkOutDate}`, {
    headers: { Accept: 'application/json' },
  })
  const rooms = res.json()

  return rooms
}

export default null
