export const fetchAvailableRooms = async ({
  room_type_id,
  check_in_date,
  check_out_date,
  reservation_id,
}: App.Http.Requests.Admin.RoomAvailabilityCheckRequest) => {
  const endpoint = '/api/admin/room_availability'
  const query = new URLSearchParams({
    room_type_id: room_type_id.toString(),
    check_in_date,
    check_out_date,
    reservation_id: reservation_id?.toString() ?? '',
  })

  const res = await fetch(`${endpoint}?${query.toString()}`, {
    headers: { Accept: 'application/json' },
  })
  const rooms =
    (await res.json()) as App.Http.Responses.Admin.RoomAvailabilityCheckResponseRoom[]

  return rooms
}

export default null
