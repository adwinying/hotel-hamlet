<?php

namespace App\Models;

use App\Actions\Reservation\FilterReservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id',
        'check_in_date',
        'check_out_date',
        'guest_name',
        'guest_email',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'check_in_date'  => 'date:Y-m-d',
        'check_out_date' => 'date:Y-m-d',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pin',
    ];

    public function scopeFilter(Builder $query, array $params)
    {
        return app(FilterReservation::class)->execute($query, $params);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
