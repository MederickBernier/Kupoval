<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to load social links into the session if they are not already present.
 *
 * This middleware checks if the session has the 'social_links' key. If not, it retrieves
 * the social media links from the settings table and stores them in the session.
 *
 * @param  \Illuminate\Http\Request  $request  The incoming HTTP request.
 * @param  \Closure  $next  The next middleware to call.
 * @return \Symfony\Component\HttpFoundation\Response  The HTTP response.
 */
class LoadSocialLinks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!session()->has('social_links')) {
            $socialKeys = [
                'social_facebook',
                'social_twitter',
                'social_instagram',
                'social_tiktok',
                'social_youtube',
                'social_linkedin'
            ];

            $socialLinks = Setting::whereIn('key', $socialKeys)->pluck('value', 'key');

            session(['social_links' => $socialLinks]);
        }

        return $next($request);
    }
}
