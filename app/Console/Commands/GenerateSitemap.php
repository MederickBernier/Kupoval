<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Générer un sitemap XML avec toutes les routes classées par accessibilité';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Create sections
        $publicRoutes = [];
        $userRoutes = [];
        $adminRoutes = [];

        // Get all defined routes
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('GET', $route->methods());
        });

        foreach ($routes as $route) {
            $uri = $route->uri();
            $url = url($uri);

            // Get the middleware stack for the route
            $middlewares = $route->gatherMiddleware();

            // Categorize routes
            if (in_array('auth', $middlewares)) {
                if (in_array('can:admin', $middlewares)) {
                    $adminRoutes[] = $url;
                } else {
                    $userRoutes[] = $url;
                }
            } else {
                $publicRoutes[] = $url;
            }
        }

        // Add public routes
        foreach ($publicRoutes as $url) {
            $sitemap->add(Url::create($url)->setPriority(1.0)->setChangeFrequency('daily'));
        }

        // Add user routes
        foreach ($userRoutes as $url) {
            $sitemap->add(Url::create($url)->setPriority(0.8)->setChangeFrequency('weekly'));
        }

        // Add admin routes
        foreach ($adminRoutes as $url) {
            $sitemap->add(Url::create($url)->setPriority(0.5)->setChangeFrequency('monthly'));
        }

        // Save sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap généré avec succès et sauvegardé dans public/sitemap.xml');
    }
}
