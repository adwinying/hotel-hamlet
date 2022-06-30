<?php

namespace App\Models;

use App\Actions\RoomType\FilterRoomType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RoomType
 *
 * @property int                             $id
 * @property int                             $hotel_id
 * @property string                          $name
 * @property int                             $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Hotel|null $hotel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 *
 * @method static \Database\Factories\RoomTypeFactory factory(...$parameters)
 * @method static Builder|RoomType filter(array $params)
 * @method static Builder|RoomType newModelQuery()
 * @method static Builder|RoomType newQuery()
 * @method static \Illuminate\Database\Query\Builder|RoomType onlyTrashed()
 * @method static Builder|RoomType query()
 * @method static Builder|RoomType whereCreatedAt($value)
 * @method static Builder|RoomType whereDeletedAt($value)
 * @method static Builder|RoomType whereHotelId($value)
 * @method static Builder|RoomType whereId($value)
 * @method static Builder|RoomType whereName($value)
 * @method static Builder|RoomType wherePrice($value)
 * @method static Builder|RoomType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|RoomType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|RoomType withoutTrashed()
 * @mixin \Eloquent
 */
class RoomType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hotel_id',
        'name',
        'price',
    ];

    /**
     * @param Builder<RoomType>      $query
     * @param array<string, ?scalar> $params
     * @return Builder<RoomType>
     */
    public function scopeFilter(Builder $query, array $params): Builder
    {
        return app(FilterRoomType::class)->execute($query, $params);
    }

    /**
     * @return BelongsTo<Hotel, RoomType>
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * @return HasMany<Room>
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
