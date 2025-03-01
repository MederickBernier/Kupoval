<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artwork;

/**
 * Class Category
 *
 * This model represents a category in the application.
 * It uses the HasFactory and SoftDeletes traits.
 *
 * @property string $name The name of the category.
 * @property string $description A brief description of the category.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function artworks(){
        return $this->belongsToMany(Artwork::class, 'artwork_categories')->withTimestamps()->withTrashed();
    }
}
