<?php

namespace App\Models;

use App\Actions\RoomType\FilterRoomType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id',
        'name',
    ];

    public function scopeFilter(Builder $query, array $params)
    {
        return app(FilterRoomType::class)->execute($query, $params);
    }
}
