<?php

namespace App\Models;

use App\Actions\Reservation\FilterReservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Reservation
 *
 * @property int                             $id
 * @property int                             $room_id
 * @property \Illuminate\Support\Carbon      $check_in_date
 * @property \Illuminate\Support\Carbon      $check_out_date
 * @property string                          $guest_name
 * @property string                          $guest_email
 * @property string                          $pin
 * @property string                          $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Room|null $room
 *
 * @method static \Database\Factories\ReservationFactory factory(...$parameters)
 * @method static Builder|Reservation filter(array $params)
 * @method static Builder|Reservation newModelQuery()
 * @method static Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Reservation onlyTrashed()
 * @method static Builder|Reservation query()
 * @method static Builder|Reservation whereCheckInDate($value)
 * @method static Builder|Reservation whereCheckOutDate($value)
 * @method static Builder|Reservation whereCreatedAt($value)
 * @method static Builder|Reservation whereDeletedAt($value)
 * @method static Builder|Reservation whereGuestEmail($value)
 * @method static Builder|Reservation whereGuestName($value)
 * @method static Builder|Reservation whereId($value)
 * @method static Builder|Reservation wherePin($value)
 * @method static Builder|Reservation whereRemarks($value)
 * @method static Builder|Reservation whereRoomId($value)
 * @method static Builder|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Reservation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Reservation withoutTrashed()
 * @mixin \Eloquent
 */
class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_id',
        'check_in_date',
        'check_out_date',
        'guest_name',
        'guest_email',
        'pin',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_date'  => 'date:Y-m-d',
        'check_out_date' => 'date:Y-m-d',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin',
    ];

    /**
     * @param Builder<Reservation> $query
     * @param array<string, mixed> $params
     * @return Builder<Reservation>
     */
    public function scopeFilter(Builder $query, array $params): Builder
    {
        return app(FilterReservation::class)->execute($query, $params);
    }

    /**
     * @return BelongsTo<Room, Reservation>
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
