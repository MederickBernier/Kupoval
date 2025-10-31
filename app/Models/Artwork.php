<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Artwork
 *
 * This model represents an artwork in the application.
 * It uses the HasFactory and SoftDeletes traits.
 *
 * @property int $id
 * @property int $artist_id
 * @property string $name
 * @property string $series_name
 * @property int $creation_year
 * @property string $description
 * @property float $height
 * @property float $width
 * @property string $medium
 * @property string $technique_notes
 * @property string $dimensions
 * @property string $depth
 * @property string $weight
 * @property string $edition_info
 * @property string $condition
 * @property string $provenance
 * @property bool $is_framed
 * @property string $framing_details
 * @property string $care_instructions
 * @property string $image
 * @property float $initial_price
 * @property bool $is_on_sale
 * @property bool $is_featured
 * @property bool $is_for_event
 * @property int|null $event_id
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork newQuery()
 * @method static \Illuminate\Database\Query\Builder|Artwork onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereIsOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereIsForEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Artwork withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Artwork withoutTrashed()
 *
 * @property-read \App\Models\Artist $artist
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\Event|null $event
 */
class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'name',
        'series_name',
        'creation_year',
        'description',
        'height',
        'width',
        'medium',
        'technique_notes',
        'dimensions',
        'depth',
        'weight',
        'edition_info',
        'condition',
        'provenance',
        'is_framed',
        'framing_details',
        'care_instructions',
        'image',
        'initial_price',
        'is_on_sale',
        'is_featured',
        'is_for_event',
        'event_id',
        'slug',
    ];

    protected $casts = [
        'creation_year' => 'integer',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'initial_price' => 'decimal:2',
        'is_on_sale' => 'boolean',
        'is_featured' => 'boolean',
        'is_for_event' => 'boolean',
        'is_framed' => 'boolean',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'artwork_categories')->withTimestamps()->withTrashed();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
