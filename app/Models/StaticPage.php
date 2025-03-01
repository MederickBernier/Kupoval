<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class StaticPage
 *
 * This model represents a static page in the application.
 * It uses the HasFactory trait for factory support.
 *
 * @property string $slug The unique identifier for the static page.
 * @property string $title The title of the static page.
 * @property string $content The content of the static page.
 * @property string $meta_description The meta description for SEO purposes.
 */
class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
    ];
}
