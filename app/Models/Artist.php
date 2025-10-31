<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artwork;
use Illuminate\Support\Str;

/**
 * Class Artist
 *
 * This model represents an artist and includes functionality for handling
 * artist-related data and relationships.
 *
 * Properties:
 * @property string $first_name The first name of the artist.
 * @property string $last_name The last name of the artist.
 * @property string $name The full name of the artist.
 * @property string $bio A short biography of the artist.
 * @property string $artist_statement Professional artist statement.
 * @property string $exhibition_history History of exhibitions and shows.
 * @property string $awards Awards and recognitions received.
 * @property string $studio_location Location of the artist's studio.
 * @property string $profile_video_url URL to artist's profile video.
 * @property array $specialties Array of artistic specialty areas.
 * @property array $techniques Array of artistic techniques used.
 * @property int $experience_years Years of artistic experience.
 * @property string $photo The URL to the artist's photo.
 * @property string $email The email address of the artist.
 * @property string $website The website URL of the artist.
 * @property string $facebook The Facebook profile URL of the artist.
 * @property string $instagram The Instagram profile URL of the artist.
 * @property string $twitter The Twitter profile URL of the artist.
 * @property string $tiktok The TikTok profile URL of the artist.
 * @property string $youtube The YouTube channel URL of the artist.
 * @property string $slug The URL-friendly version of the artist's name.
 *
 * Methods:
 * @method \Illuminate\Database\Eloquent\Relations\HasMany artworks() Defines a one-to-many relationship with the Artwork model.
 * @method static void boot() Boot method to handle model events, such as creating a unique slug for the artist.
 * @method string getRouteKeyName() Returns the name of the route key for the model, which is 'slug'.
 *
 * Traits:
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory
 * @use \Illuminate\Database\Eloquent\SoftDeletes
 */
class Artist extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'bio',
        'artist_statement',
        'exhibition_history',
        'awards',
        'studio_location',
        'profile_video_url',
        'specialties',
        'techniques',
        'experience_years',
        'photo',
        'email',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'tiktok',
        'youtube',
        'slug',
    ];

    protected $casts = [
        'specialties' => 'array',
        'techniques' => 'array',
        'experience_years' => 'integer',
    ];

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public static function boot(){
        parent::boot();
        static::creating(function($artist){
            $baseSlug = $artist->name ?? $artist->first_name . '-' . $artist->last_name;
            $artist->slug = Str::slug($baseSlug);

            $count = static::where('slug', 'LIKE', "{$artist->slug}%")->count();
            if ($count > 0) {
                $artist->slug .= '-' . ($count + 1);
            }
        });
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
