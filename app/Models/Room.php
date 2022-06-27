<?php

namespace App\Models;

use App\Actions\Room\FilterRoom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Room
 *
 * @property int                             $id
 * @property int                             $room_type_id
 * @property string                          $room_no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @property-read \App\Models\RoomType|null $roomType
 *
 * @method static \Database\Factories\RoomFactory factory(...$parameters)
 * @method static Builder|Room filter(array $params)
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static \Illuminate\Database\Query\Builder|Room onlyTrashed()
 * @method static Builder|Room query()
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereDeletedAt($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereRoomNo($value)
 * @method static Builder|Room whereRoomTypeId($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Room withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Room withoutTrashed()
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_type_id',
        'room_no',
    ];

    /**
     * @param Builder<Room>        $query
     * @param array<string, mixed> $params
     * @return Builder<Room>
     */
    public function scopeFilter(Builder $query, array $params): Builder
    {
        return app(FilterRoom::class)->execute($query, $params);
    }

    /**
     * @return BelongsTo<RoomType, Room>
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * @return HasMany<Reservation>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
