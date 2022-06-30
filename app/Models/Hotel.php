<?php

namespace App\Models;

use App\Actions\Hotel\FilterHotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Hotel
 *
 * @property int                             $id
 * @property string                          $name
 * @property bool                            $is_hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Database\Factories\HotelFactory factory(...$parameters)
 * @method static Builder|Hotel filter(array $params)
 * @method static Builder|Hotel newModelQuery()
 * @method static Builder|Hotel newQuery()
 * @method static \Illuminate\Database\Query\Builder|Hotel onlyTrashed()
 * @method static Builder|Hotel query()
 * @method static Builder|Hotel whereCreatedAt($value)
 * @method static Builder|Hotel whereDeletedAt($value)
 * @method static Builder|Hotel whereId($value)
 * @method static Builder|Hotel whereIsHidden($value)
 * @method static Builder|Hotel whereName($value)
 * @method static Builder|Hotel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Hotel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Hotel withoutTrashed()
 * @mixin \Eloquent
 */
class Hotel extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_hidden',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    /**
     * @param Builder<Hotel>         $query
     * @param array<string, ?scalar> $params
     * @return Builder<Hotel>
     */
    public function scopeFilter(Builder $query, array $params): Builder
    {
        return app(FilterHotel::class)->execute($query, $params);
    }
}
