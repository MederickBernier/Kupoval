<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
