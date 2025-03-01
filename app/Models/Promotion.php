<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Promotion
 *
 * This model represents a promotion in the application.
 * It uses the HasFactory and SoftDeletes traits.
 *
 * @property string $name The name of the promotion.
 * @property string $code The code for the promotion.
 * @property string $description A description of the promotion.
 * @property float $discount_percentage The percentage discount offered by the promotion.
 * @property float $discount_amount The amount discount offered by the promotion.
 * @property int $usage_limit The maximum number of times the promotion can be used.
 * @property bool $is_active Indicates if the promotion is currently active.
 * @property \Illuminate\Support\Carbon $start_date The start date of the promotion.
 * @property \Illuminate\Support\Carbon $end_date The end date of the promotion.
 * @property int $created_by The ID of the user who created the promotion.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion newQuery()
 * @method static \Illuminate\Database\Query\Builder|Promotion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Promotion query()
 * @method static \Illuminate\Database\Query\Builder|Promotion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Promotion withoutTrashed()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Artwork[] $artworks
 * @property-read \App\Models\User $creator
 */
class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_percentage',
        'discount_amount',
        'usage_limit',
        'is_active',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_promotions')->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
